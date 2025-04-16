<?php
$host = "localhost";
$db = "cinemaclub7";
$user = "root";
$pass = "";
$apiKey = "acab06919125f555727dedf0d5342357";
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

function fetchMovieDetails($movieId, $apiKey) {
    $url = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=credits";
    $json = file_get_contents($url);
    return $json ? json_decode($json, true) : null;
}

function getDirector($credits) {
    foreach ($credits['crew'] as $crewMember) {
        if ($crewMember['job'] === 'Director') {
            return $crewMember['name'];
        }
    }
    return 'Desconocido';
}

function getAwardsFromWikidata($title) {
    $query = <<<SPARQL
SELECT ?awardLabel WHERE {
  ?film rdfs:label "$title"@en.
  ?film p:P166 ?awardStatement.
  ?awardStatement ps:P166 ?award.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "en". }
}
SPARQL;

    $url = 'https://query.wikidata.org/sparql?format=json&query=' . urlencode($query);
    $response = file_get_contents($url);
    if (!$response) return [];

    $data = json_decode($response, true);
    $awards = [];
    foreach ($data['results']['bindings'] as $binding) {
        $awards[] = $binding['awardLabel']['value'];
    }
    return $awards;
}

function getNominationsFromWikidata($title) {
    $query = <<<SPARQL
SELECT ?nominationLabel WHERE {
  ?film rdfs:label "$title"@en.
  ?film p:P1411 ?statement.
  ?statement ps:P1411 ?nomination.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "en". }
}
SPARQL;

    $url = 'https://query.wikidata.org/sparql?format=json&query=' . urlencode($query);
    $response = file_get_contents($url);
    if (!$response) return [];

    $data = json_decode($response, true);
    $nominations = [];
    foreach ($data['results']['bindings'] as $binding) {
        $nominations[] = $binding['nominationLabel']['value'];
    }
    return $nominations;
}

function getFestivalsFromWikidata($title) {
    $query = <<<SPARQL
SELECT ?festivalLabel WHERE {
  ?film rdfs:label "$title"@en.
  {
    ?film wdt:P4634 ?festival.
  }
  UNION
  {
    ?film wdt:P1344 ?festival.
  }
  SERVICE wikibase:label { bd:serviceParam wikibase:language "en". }
}
SPARQL;

    $url = 'https://query.wikidata.org/sparql?format=json&query=' . urlencode($query);
    $response = file_get_contents($url);
    if (!$response) return [];

    $data = json_decode($response, true);
    $festivals = [];
    foreach ($data['results']['bindings'] as $binding) {
        $festivals[] = $binding['festivalLabel']['value'];
    }
    return $festivals;
}

function saveFilm($pdo, $film) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM films WHERE title = ? AND release_date = ?");
    $stmt->execute([$film['title'], $film['release_date']]);
    if ($stmt->fetchColumn() > 0) {
        echo "La película '{$film['title']}' ya existe.<br>";
        return;
    }

    $insert = $pdo->prepare("
        INSERT INTO films (
            title, directedBy, genre, origin_country, duration,
            castCrew, release_date, frame, individualRate, globalRate,
            awards, overview, nominations, festivals, vote_average, original_language
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([ 
        $film['title'],
        $film['directedBy'],
        $film['genre'],
        $film['country'],
        $film['duration'],
        $film['castCrew'],
        $film['release_date'],
        $film['frame'],
        0,
        0,
        implode(', ', $film['awards']),
        $film['overview'],
        implode(', ', $film['nominations']),
        implode(', ', $film['festivals']),
        $film['vote_average'],
        $film['original_language']
    ]);

    echo "Película '{$film['title']}' insertada.<br>";
}

function fetchMoviesFromPage($page, $apiKey, $yearStart, $yearEnd) {
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&primary_release_date.gte=$yearStart-01-01&primary_release_date.lte=$yearEnd-12-31&page=$page&sort_by=popularity.desc";
    $json = file_get_contents($url);
    return $json ? json_decode($json, true)['results'] : [];
}

set_time_limit(0);
echo "Iniciando proceso...<br>";

$yearEnd = date('Y');
$yearStart = $yearEnd - 2;
$pages = 100;

for ($page = 1; $page <= $pages; $page++) {
    echo "Procesando página $page de $pages...<br>";
    $movies = fetchMoviesFromPage($page, $apiKey, $yearStart, $yearEnd);

    foreach ($movies as $movie) {
        $details = fetchMovieDetails($movie['id'], $apiKey);
        if (!$details) continue;

        $credits = $details['credits'];
        $director = getDirector($credits);
        $cast = array_slice(array_column($credits['cast'], 'name'), 0, 5);
        $genres = array_column($details['genres'], 'name');
        $countries = array_column($details['production_countries'], 'name');

        // Obtener premios, nominaciones y festivales
        $awards = getAwardsFromWikidata($details['title']);
        $nominations = getNominationsFromWikidata($details['title']);
        $festivals = getFestivalsFromWikidata($details['title']);

        // Prepara los datos para insertar
        $filmData = [
            'title' => $details['title'],
            'directedBy' => $director,
            'genre' => implode(', ', $genres),
            'country' => implode(', ', $countries),
            'duration' => $details['runtime'],
            'castCrew' => implode(', ', $cast),
            'release_date' => $details['release_date'],
            'frame' => $details['poster_path'] ? 1 : 0,
            'awards' => $awards,
            'nominations' => $nominations,
            'festivals' => $festivals,
            'overview' => $details['overview'],
            'vote_average' => $details['vote_average'],
            'original_language' => $details['original_language']
        ];

        // Guardar la película
        saveFilm($pdo, $filmData);
    }
}

echo "Proceso finalizado.<br>";
?>



