<?php
// film.php (página de detalle de una película con voto individual y global actualizados)

require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
// 1) Cargar y validar sesión + usuario
require_once dirname(__DIR__) . '/api/comprobar.sesion.php';
require_once dirname(__DIR__). '/modal.login.php';


global $actualUser;

//  inicializar $idUser solo si $actualUser es un objeto válido *****
$idUser = 0;
if (isset($actualUser) && is_object($actualUser) && method_exists($actualUser, 'getidUser')) {
    // si el usuario está logueado, getidUser() devolverá su id, si no, 0 o null
    $idUser = $actualUser->getidUser() ?? 0;
}

// 2) Obtener film_id desde GET y validar
$filmId = filter_input(INPUT_GET, 'film_id', FILTER_VALIDATE_INT);
if (!$filmId) {
    die('ID de película no válido.');
}

$gestorDB = new HandlerDB();

// 3) Consultar datos de la película (sin individualRate)
$filmData = $gestorDB->getRecords(
    TABLE_FILM,
    ['idFilm','title','directedBy','genre','origin_country','original_language',
     'overview','duration','castCrew','release_date','frame','awards',
     'nominations','festivals','vote_average','globalRate'],
    'idFilm = :f',
    [':f' => $filmId],
    null,
    'FETCH_ASSOC'
);
if (!$filmData) {
    die('Película no encontrada.');
}
$film = $filmData[0];

// 4) Obtener voto individual del usuario, si está logueado
if ($idUser) {
    $ir = $gestorDB->getRecords(
        TABLE_INDIVIDUAL_RATE,
        ['rate'],
        'idFilm = :f AND idUser = :u',
        [':f' => $filmId, ':u' => $idUser],
        null,
        'FETCH_ASSOC'
    );
    $film['individualRate'] = $ir ? (float)$ir[0]['rate'] : null;
} else {
    $film['individualRate'] = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<title><?php echo CONFIG_GENERAL['TITULO_WEB']; ?> -Película</title>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($film['title']); ?></title>
    <?php require_once dirname(__DIR__) . '/header/header.php'; ?>
    <?php require_once dirname(__DIR__) . '/menu/menu.php'; ?>

    <!-- RateIt CSS y JS -->
    <link href="../films/assets/rateit.css" rel="stylesheet">
    <script src="../films/assets/jquery.rateit.min.js"></script>
</head>
<body class="film-page">
  <div class="film-container">
    <header class="film-header" style="background-image: url('<?php echo $film['frame']; ?>')">
      <div class="header-overlay"></div>
      <div class="header-content">
        <h1 class="film-title"><?php echo htmlspecialchars($film['title']); ?></h1>
        <span class="film-year"><?php echo date('Y', strtotime($film['release_date'])); ?></span>
      </div>
    </header>

    <section class="film-main">
      <div class="film-poster">
        <img src="<?php echo $film['frame']; ?>" alt="Póster de <?php echo htmlspecialchars($film['title']); ?>">
      </div>

      <div class="film-info">
        <ul class="film-details">
          <li><strong>Género:</strong> <?php echo htmlspecialchars($film['genre']); ?></li>
          <li><strong>Dirigida por:</strong> <?php echo htmlspecialchars($film['directedBy']); ?></li>
          <li><strong>País:</strong> <?php echo htmlspecialchars($film['origin_country']); ?></li>
          <li><strong>Idioma:</strong> <?php echo htmlspecialchars($film['original_language']); ?></li>
          <li><strong>Duración:</strong> <?php echo $film['duration']; ?> min</li>
          <li><strong>Reparto:</strong> <?php echo nl2br(htmlspecialchars($film['castCrew'])); ?></li>
          <li><strong>Estreno:</strong> <?php echo date('d M Y', strtotime($film['release_date'])); ?></li>
          <li><strong>Premios:</strong> <?php echo htmlspecialchars($film['awards']); ?></li>
          <li><strong>Nominaciones:</strong> <?php echo htmlspecialchars($film['nominations']); ?></li>
          <li><strong>Festivales:</strong> <?php echo htmlspecialchars($film['festivals']); ?></li>
        </ul>

        <div class="film-overview">
          <h2>Sinopsis</h2>
          <p><?php echo htmlspecialchars($film['overview']); ?></p>
        </div>

        <div class="film-ratings">
          <span class="rating-tag">
            Media TMDB: <span id="external-average"><?php echo $film['vote_average']; ?></span>
          </span>
          <span class="rating-tag">
            Tu voto: <span id="user-rating"><?php echo $film['individualRate'] !== null ? $film['individualRate'] : '&ndash;'; ?></span>
          </span>
          <span class="rating-tag">
            Global: <span id="global-rating"><?php echo number_format((float)$film['globalRate'], 1); ?></span>
          </span>
        </div>
      </div>

      <!-- Form para votar SOLO si el usuario está logueado -->
      <?php if ($idUser > 0): ?>
        <form id="voteForm" method="POST" action="vote.php" class="vote-form">
          <label for="rating">Vota la película:</label>
          <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
              <input type="radio"
                    id="star<?php echo $i; ?>"
                    name="rating"
                    value="<?php echo $i; ?>"
                    <?php echo ($film['individualRate'] == $i ? 'checked' : ''); ?>>
              <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> estrella<?php echo $i>1?'s':''; ?>"></label>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="film_id" value="<?php echo $film['idFilm']; ?>">
          <button type="submit" class="btn btn-warning mt-2">Votar</button>
        </form>
        <?php else: ?>
          <p class="not-log-vote-msn">¡ Inicia sesión o crea tu cuenta para votar !</p>
        <?php endif; ?>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- JS de votación AJAX -->
  <script src="../films/js/functions.vote.js"></script>
</body>
</html>