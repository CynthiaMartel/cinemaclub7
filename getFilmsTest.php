<?php
$host = "localhost";
$db = "cinemaclub7"; 
$user = "root";
$pass = "";
$apiKey = "acab06919125f555727dedf0d5342357"; // Reemplaza con tu API Key de TMDb

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

// Lista reducida de películas premiadas (solo 4)
$awardWinningMovies = [
    [
        'title' => 'Oppenheimer',
        'awards' => ['Oscar: Best Picture', 'Oscar: Best Director'],
        'nominations' => ['Oscar: Best Actor'],
        'festivals' => ['Oscars']
    ],
    [
        'title' => 'Poor Things',
        'awards' => ['Oscar: Best Actress'],
        'nominations' => ['Oscar: Best Picture', 'BAFTA: Best Film'],
        'festivals' => ['Oscars', 'BAFTA']
    ],
    [
        'title' => 'The Zone of Interest',
        'awards' => ['BAFTA: Outstanding British Film', 'Oscar: Best International Feature'],
        'nominations' => ['Oscar: Best Picture'],
        'festivals' => ['BAFTA', 'Oscars']
    ],
    [
        'title' => 'Anatomy of a Fall',
        'awards' => ['Cannes: Palme d\'Or'],
        'nominations' => ['Oscar: Best Original Screenplay'],
        'festivals' => ['Cannes', 'Oscars']
    ]
];

// Función para buscar los detalles de cada película
function fetchMovieDetails($title, $apiKey) {
    $query = urlencode($title);
    $searchUrl = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=$query";
    $searchResponse = file_get_contents($searchUrl);
    $searchData = json_decode($searchResponse, true);

    if (!empty($searchData['results'])) {
        $movieId = $searchData['results'][0]['id'];
        $detailsUrl = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=credits";
        $detailsResponse = file_get_contents($detailsUrl);
        return json_decode($detailsResponse, true);
    }

    return null;
}

// Obtener director
function getDirector($credits) {
    foreach ($credits['crew'] as $crewMember) {
        if ($crewMember['job'] === 'Director') {
            return $crewMember['name'];
        }
    }
    return 'Desconocido';
}

// Insertar en la base de datos
function saveFilm($pdo, $filmData) {
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM films WHERE title = ?");
    $checkStmt->execute([$filmData['title']]);
    if ($checkStmt->fetchColumn() > 0) {
        echo "🔁 La película '{$filmData['title']}' ya existe en la base de datos.<br>";
        return;
    }

    $insertStmt = $pdo->prepare("
        INSERT INTO films (
            title, directedBy, genre, country, description, duration,
            castCrew, realeses, frame, individualRate, globalRate,
            awards, nominations, festivals
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insertStmt->execute([
        $filmData['title'],
        $filmData['directedBy'],
        $filmData['genre'],
        $filmData['country'],
        $filmData['description'],
        $filmData['duration'],
        $filmData['castCrew'],
        $filmData['realeses'],
        0, // frame
        0, // individualRate
        0, // globalRate
        implode(', ', $filmData['awards']),
        implode(', ', $filmData['nominations']),
        implode(', ', $filmData['festivals'])
    ]);

    echo "✅ Película '{$filmData['title']}' insertada correctamente.<br>";
}

// Procesar cada una
foreach ($awardWinningMovies as $movie) {
    $details = fetchMovieDetails($movie['title'], $apiKey);
    if ($details) {
        $filmData = [
            'title' => $details['title'] ?? $movie['title'],
            'directedBy' => isset($details['credits']) ? getDirector($details['credits']) : 'Desconocido',
            'genre' => isset($details['genres']) ? implode(', ', array_column($details['genres'], 'name')) : '',
            'country' => isset($details['production_countries'][0]['name']) ? $details['production_countries'][0]['name'] : '',
            'description' => $details['overview'] ?? '',
            'duration' => $details['runtime'] ?? 0,
            'castCrew' => isset($details['credits']['cast']) ? implode(', ', array_column(array_slice($details['credits']['cast'], 0, 5), 'name')) : '',
            'realeses' => $details['release_date'] ?? '',
            'awards' => $movie['awards'],
            'nominations' => $movie['nominations'],
            'festivals' => $movie['festivals']
        ];

        saveFilm($pdo, $filmData);
    } else {
        echo "⚠️ No se encontraron detalles para '{$movie['title']}'<br>";
    }
}
?>
<?php echo "¡Apache está funcionando y ve esta carpeta!"; ?>

