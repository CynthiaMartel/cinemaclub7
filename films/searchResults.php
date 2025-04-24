<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/config/config.globales.php'; 
require_once dirname(__DIR__) . '/db/class.HandlerDB.php';
require_once dirname(__DIR__) . '/api/comprobar.sesion.php';
require_once  dirname(__DIR__). '/class/class.User.php';
require_once dirname(__DIR__). '/modal.login.php';

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
<title><?php echo CONFIG_GENERAL['TITULO_WEB']; ?> -Listado Películas</title>
<?php require_once dirname(__DIR__) .'/header/header.php'; ?>
<?php require_once dirname(__DIR__) . '/menu/menu.php'; ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1 class="ms-2 ms-sm-3 ms-md-5 ms-lg-5 text-white">Resultados de Búsqueda</h1>
 

</head>
<body class="film-search">
    <h2 class="ms-2 ms-sm-3 ms-md-5 ms-lg-5 text-white">Resultados de Búsqueda para "<?php echo htmlspecialchars($query); ?>"</h2>

    <div class="film-container">
        <?php if (!empty($output)): ?>
            <?php foreach ($output as $film): ?>
                <div class="film-card">
                    <?php if (!empty($film['frame'])): ?>
                        <img src="<?php echo htmlspecialchars($film['frame']); ?>"
                             alt="Imagen de <?php echo htmlspecialchars($film['title']); ?>" />
                    <?php endif; ?>
                    <div class="film-details">
                        <a class="a" href="index.php?film_id=<?php echo htmlspecialchars($film['idFilm']); ?>">
                            <h2 class="film-title">
                                <?php echo htmlspecialchars($film['title']); ?>
                            </h2>
                        </a>
                        <p class="film-overview">
                            <?php echo htmlspecialchars($film['overview']); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>