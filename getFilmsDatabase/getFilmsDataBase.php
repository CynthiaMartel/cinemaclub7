<?php
$host = "localhost";
$db = "cinemaclub7";
$user = "root";
$pass = "";
$apiKey = "acab06919125f555727dedf0d5342357";
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

function curlRequest($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "CynthiaMovieBot/1.0 (https://cinemaclub7.local)");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo "cURL error: " . curl_error($ch) . "<br>";
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    return $response;
}

function log_wikidata_request($title, $property) {
    $logFile = __DIR__ . '/wikidata_requests.log';
    $timestamp = date('Y-m-d H:i:s');
    $entry = "$timestamp | Title: $title | Property: $property" . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND);
}

function fetchMovieDetails($movieId, $apiKey) {
    $url = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=credits";
    $json = curlRequest($url);
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

function getFromWikidata($title, $property, $label) {
    log_wikidata_request($title, $property);

    $query = <<<SPARQL
SELECT ?${label}Label WHERE {
  ?film rdfs:label "$title"@en.
  ?film p:$property ?statement.
  ?statement ps:$property ?$label.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "en". }
}
SPARQL;

    $url = 'https://query.wikidata.org/sparql?format=json&query=' . urlencode($query);
    $response = curlRequest($url);
    if (!$response) return [];

    sleep(1);
    $data = json_decode($response, true);
    $results = [];
    foreach ($data['results']['bindings'] as $binding) {
        $results[] = $binding["{$label}Label"]['value'];
    }
    return $results;
}

function getFestivalsFromWikidata($title) {
    log_wikidata_request($title, 'P4634/P1344');

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
    $response = curlRequest($url);
    if (!$response) return [];

    sleep(1);
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
            title, directedBy, genre, origin_country, original_language,
            overview, duration, castCrew, release_date, frame,
            awards, nominations, festivals, vote_average,
            individualRate, globalRate
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([ 
        $film['title'],
        $film['directedBy'],
        $film['genre'],
        $film['country'],
        $film['original_language'],
        $film['overview'],
        $film['duration'],
        $film['castCrew'],
        $film['release_date'],
        $film['frame'],
        implode(', ', $film['awards']),
        implode(', ', $film['nominations']),
        implode(', ', $film['festivals']),
        $film['vote_average'],
        0, // individualRate
        0  // globalRate
    ]);

    echo "Película '{$film['title']}' insertada.<br>";
}

function fetchMoviesFromPage($page, $apiKey, $yearStart, $yearEnd) {
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&primary_release_date.gte=$yearStart-01-01&primary_release_date.lte=$yearEnd-12-31&page=$page&sort_by=popularity.desc";
    $json = curlRequest($url);
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

        $title = $details['title'];
        $awards = getFromWikidata($title, 'P166', 'award');
        $nominations = getFromWikidata($title, 'P1411', 'nomination');
        $festivals = getFestivalsFromWikidata($title);

        $filmData = [
            'title' => $title,
            'directedBy' => $director,
            'genre' => implode(', ', $genres),
            'country' => implode(', ', $countries),
            'original_language' => $details['original_language'],
            'overview' => $details['overview'],
            'duration' => $details['runtime'],
            'castCrew' => implode(', ', $cast),
            'release_date' => $details['release_date'],
            'frame' => $details['poster_path'] 
                ? "https://image.tmdb.org/t/p/w500" . $details['poster_path'] 
                : '', // si no hay poster
            'awards' => $awards,
            'nominations' => $nominations,
            'festivals' => $festivals,
            'vote_average' => $details['vote_average']
        ];

        saveFilm($pdo, $filmData);
    }

    sleep(5);
}

echo "Proceso finalizado.<br>";
?>









