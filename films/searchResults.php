<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/config/config.globales.php'; 
require_once dirname(__DIR__) . '/db/class.HandlerDB.php';
require_once dirname(__DIR__) . '/api/comprobar.sesion.php';
require_once  dirname(__DIR__). '/class/class.User.php';

$query = $_GET['query'] ?? '';
$output = [];

if ($query) {
    $gestorDB = new HandlerDB();

    // Usamos LIKE para buscar en el título
    $where = "title LIKE :query";
    $parametrosWhere = [":query" => '%' . $query . '%'];

    // Obtenemos las películas de la base de datos que coinciden con la búsqueda
    $films = $gestorDB->getRecords(
        TABLE_FILM,
        ['idFilm','title', 'overview', 'frame'], // Puedes añadir más campos si los necesitas
        $where,
        $parametrosWhere,
        'title ASC', // Ordenar por título
        'FETCH_ASSOC' // Tipo Fetch, puede ser 'FETCH_ASSOC' o '_OBJ'
    );

    if ($films) {
        $output = $films;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<?php require_once dirname(__DIR__) .'/header/header.php'; ?>
<?php require_once dirname(__DIR__) . '/menu/menu.php'; ?>

    <meta charset="UTF-8">
    <h1 class="ms-2 ms-sm-3 ms-md-5 ms-lg-5">Resultados de Búsqueda</h1>
    <link rel="stylesheet" href="your_stylesheet.css"> <!-- Si tienes un archivo CSS -->
    <style>
        :root {
            --bg-color:rgb(41, 41, 41);
            --text-color: #ECEFF1;
            --muted-color: #B0BEC5;
            --accent-color: black;
            --font-family: 'Cabin', Arial, sans-serif;
        }

        body {
            margin: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: var(--font-family);
        }

        .film-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .film-card {
            background-color: #1c1c1c;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .film-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
        }

        .film-card img {
            width: 100%;
            height: auto;
        }

        .film-details {
            padding: 15px;
        }

        .film-title {
            font-size: 1.25rem;
            margin: 0 0 5px;
            color: var(--accent-color);
        }

        .film-overview {
            color: var(--muted-color);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <h2 class="ms-2 ms-sm-3 ms-md-5 ms-lg-5">Resultados de Búsqueda para "<?php echo htmlspecialchars($query); ?>"</h2>

    <div class="film-container">
        <?php if (!empty($output)): ?>
            <?php foreach ($output as $film): ?>
                <div class="film-card">
                    <?php if (!empty($film['frame'])): ?>
                        <img src="<?php echo htmlspecialchars($film['frame']); ?>" alt="Imagen de <?php echo htmlspecialchars($film['title']); ?>" />
                    <?php endif; ?>
                    <div class="film-details">
                        <a href="index.php?film_id=<?php echo htmlspecialchars($film['idFilm']); ?>">
                            <h2 class="film-title"><?php echo htmlspecialchars($film['title']); ?></h2>
                        </a>
                        <p class="film-overview"><?php echo htmlspecialchars($film['overview']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    </div>
</body>
</html>