<?php
$host = "localhost";
$db = "cinemaclub7"; // Reemplaza con el nombre de tu base de datos
$user = "root";
$pass = "";
$apiKey = "acab06919125f555727dedf0d5342357"; // Reemplaza con tu API Key de TMDb
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

// Función para obtener detalles de la película desde TMDb usando el ID
function fetchMovieDetails($movieId, $apiKey) {
    $detailsUrl = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=credits,awards";
    $detailsResponse = file_get_contents($detailsUrl);
    if (!$detailsResponse) {
        return null;
    }
    return json_decode($detailsResponse, true);
}

// Función para obtener el director de la película
function getDirector($credits) {
    foreach ($credits['crew'] as $crewMember) {
        if ($crewMember['job'] === 'Director') {
            return $crewMember['name'];
        }
    }
    return 'Desconocido';
}

// Función para obtener premios de la película
function getAwards($movie) {
    $awards = [];
    if (isset($movie['awards'])) {
        foreach ($movie['awards']['results'] as $award) {
            $awards[] = $award['award_name'];
        }
    }
    return $awards;
}

// Función para obtener festivales y premios desde Wikipedia (scraping)
function getFestivalsFromWikipedia($movieTitle) {
    $searchUrl = "https://en.wikipedia.org/wiki/" . urlencode($movieTitle);
    $html = @file_get_contents($searchUrl);
    if (!$html) {
        return [];
    }

    // Utilizamos la librería simple_html_dom para parsear el HTML
    include('simple_html_dom.php'); // Asegúrate de tener la librería simple_html_dom incluida
    $dom = new simple_html_dom();
    $dom->load($html);

    $festivals = [];
    foreach ($dom->find('table.wikitable') as $table) {
        foreach ($table->find('tr') as $row) {
            $cells = $row->find('td');
            if (count($cells) > 0) {
                $festival = $cells[0]->plaintext;
                $festivals[] = trim($festival);
            }
        }
    }

    return $festivals;
}

// Función para obtener premios y festivales de películas (premiadas o no)
function getManualAwardsInfo($title) {
    $awardWinningMovies = [
        [
            'title' => 'Everything Everywhere All at Once',
            'awards' => ['Oscar: Best Picture'],
            'nominations' => ['Oscar: Best Director', 'Oscar: Best Actress'],
            'festivals' => ['Oscars']
        ],
        [
            'title' => 'All Quiet on the Western Front',
            'awards' => ['BAFTA: Best Film', 'Oscar: Best International Feature'],
            'nominations' => ['Oscar: Best Picture'],
            'festivals' => ['BAFTA', 'Oscars']
        ],
        [
            'title' => 'Some Movie Without Awards',
            'awards' => [],
            'nominations' => [],
            'festivals' => getFestivalsFromWikipedia('Some Movie Without Awards')
        ],
    ];

    foreach ($awardWinningMovies as $movie) {
        if ($movie['title'] === $title) {
            return [
                'awards' => $movie['awards'],
                'nominations' => $movie['nominations'],
                'festivals' => $movie['festivals']
            ];
        }
    }

    return ['awards' => [], 'nominations' => [], 'festivals' => []];
}

// Función para insertar la película en la base de datos
function saveFilm($pdo, $filmData) {
    // Verificar si la película ya existe
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM films WHERE title = ?");
    $checkStmt->execute([$filmData['title']]);
    if ($checkStmt->fetchColumn() > 0) {
        echo "La película '{$filmData['title']}' ya existe en la base de datos.<br>";
        return;
    }

    // Insertar la película
    $insertStmt = $pdo->prepare("
        INSERT INTO films (
            title, directedBy, genre, origin_country, description, duration,
            castCrew, release_date, frame, individualRate, globalRate,
            awards, overview, nominations, festivals, vote_average, original_language
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Usamos 'poster_path' para el campo 'frame', o NULL si no está disponible
    $frame = $filmData['frame'] ?? NULL;

    $insertStmt->execute([
        $filmData['title'],
        $filmData['directedBy'],
        $filmData['genre'],
        $filmData['country'],
        $filmData['description'],
        $filmData['duration'],
        $filmData['castCrew'],
        $filmData['release_date'],
        $frame, // frame
        0, // individualRate
        0, // globalRate
        implode(', ', $filmData['awards']),
        $filmData['description'],
        implode(', ', $filmData['nominations']),
        implode(', ', $filmData['festivals']),
        $filmData['vote_average'],
        $filmData['original_language']
    ]);

    echo "Película '{$filmData['title']}' insertada correctamente.<br>";
}

// Configuramos el tiempo de ejecución para evitar el límite de tiempo
set_time_limit(0);

// Función para obtener películas de una página específica (últimos 2 años)
function fetchMoviesFromPage($page, $apiKey, $yearStart, $yearEnd) {
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&primary_release_date.gte=$yearStart-01-01&primary_release_date.lte=$yearEnd-12-31&page=$page";
    $response = file_get_contents($url);
    if (!$response) {
        return null;
    }
    return json_decode($response, true)['results'];
}

// Número total de películas a obtener
$totalMovies = 4000;
$moviesPerPage = 20; // TMDb devuelve 20 películas por página
$totalPages = ceil($totalMovies / $moviesPerPage);

// Obtener las fechas de inicio y fin para los últimos 2 años
$yearEnd = date("Y");
$yearStart = $yearEnd - 2;

// Mensaje inicial
echo "Iniciando el proceso de inserción de películas...<br>";

// Procesar las películas de varias páginas
$moviesFetched = 0;
for ($page = 1; $page <= $totalPages; $page++) {
    echo "Obteniendo página $page de $totalPages...<br>";

    // Obtener las películas de la página actual
    $movies = fetchMoviesFromPage($page, $apiKey, $yearStart, $yearEnd);
    if (!$movies) {
        echo "No se pudo obtener películas de la página $page.<br>";
        continue;
    }

    // Procesar cada película obtenida
    foreach ($movies as $movie) {
        $details = fetchMovieDetails($movie['id'], $apiKey);
        if ($details) {
            $manualAwards = getManualAwardsInfo($movie['title']); // Obtener premios y festivales

            $filmData = [
                'title' => $details['title'] ?? $movie['title'],
                'directedBy' => isset($details['credits']) ? getDirector($details['credits']) : 'Desconocido',
                'genre' => isset($details['genres']) ? implode(', ', array_column($details['genres'], 'name')) : '',
                'country' => isset($details['production_countries'][0]['name']) ? $details['production_countries'][0]['name'] : '',
                'description' => $details['overview'] ?? '',
                'duration' => $details['runtime'] ?? 0,
                'castCrew' => isset($details['credits']['cast']) ? implode(', ', array_column(array_slice($details['credits']['cast'], 0, 5), 'name')) : '',
                'release_date' => $details['release_date'] ?? '',
                'awards' => $manualAwards['awards'], // Premios obtenidos
                'nominations' => $manualAwards['nominations'],
                'festivals' => $manualAwards['festivals'],
                'vote_average' => $details['vote_average'] ?? 0,
                'original_language' => $details['original_language'] ?? ''
            ];

            saveFilm($pdo, $filmData); // Insertar en la base de datos
        }
    }
}






