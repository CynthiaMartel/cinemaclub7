<?php 
    require_once __DIR__ . '/../config/config.globales.php';
    require_once __DIR__ . '/../db/class.HandlerDB.php';

    $filmId = $_GET['film_id'] ?? null;
    $where = "idFilm = ".$filmId;

    $gestorDB = new HandlerDB();

    $film = $gestorDB->getRecords(
        TABLE_FILM,
        [
            'idFilm',
            'title',
            'directedBy',
            'genre',
            'origin_country',
            'original_language',
            'overview',
            'duration',
            'castCrew',
            'release_date',
            'frame',
            'awards',
            'nominations',
            'festivals',
            'vote_average',
            'individualRate',
            'globalRate'
        ],
        $where,
        [],
        null,
        'FETCH_ASSOC'
    );

    $emojilang = "🇬🇧";
?>


<head>
    <style>
        :root {
  --bg-color: #141414;
  --text-color: #ECEFF1;
  --muted-color: #B0BEC5;
  --accent-color: #F2B01E;
  --font-family: 'Helvetica Neue', Arial, sans-serif;
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
}

/* HEADER */
.film-header {
  position: relative;
  height: 350px;
  background-size: cover;
  background-position: center;
}

.header-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, rgba(20,20,20,0.6), rgba(20,20,20,0.9));
}

.header-content {
  position: absolute;
  bottom: 20px;
  left: 30px;
  color: var(--text-color);
}

.film-title {
  margin: 0;
  font-size: 2.5rem;
  font-weight: bold;
}

.film-year {
  font-size: 1.1rem;
  color: var(--muted-color);
  margin-left: 0.5rem;
}

/* CONTENIDO PRINCIPAL */
.film-main {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 30px;
  padding: 40px 30px;
}

.film-poster img {
  width: 100%;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.5);
}

.film-info {
  display: flex;
  flex-direction: column;
}

.film-details {
  list-style: none;
  padding: 0;
  margin: 0 0 30px;
  line-height: 1.6;
}

.film-details li strong {
  color: var(--text-color);
}

.film-overview h2 {
  margin-top: 0;
  margin-bottom: 10px;
  color: var(--accent-color);
  font-size: 1.5rem;
}

.film-overview p {
  margin: 0;
  color: var(--text-color);
}

.film-ratings {
  margin-top: auto;
}

.rating-tag {
  display: inline-block;
  margin-right: 10px;
  padding: 6px 12px;
  background-color: rgba(255,255,255,0.1);
  border-radius: 4px;
  font-weight: 500;
}
    </style>
</head>
<body>
  <div class="film-container">
    <!-- HEADER CON IMAGEN DE PORTADA -->
    <header class="film-header" style="background-image: url('<?php echo $film[0]['frame']; ?>')">
      <div class="header-overlay"></div>
      <div class="header-content">
        <h1 class="film-title"><?php echo htmlspecialchars($film[0]['title']); ?></h1>
        <span class="film-year"><?php echo date('Y', strtotime($film[0]['release_date'])); ?></span>
      </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <section class="film-main">
      <!-- PÓSTER -->
      <div class="film-poster">
        <img
          src="<?php echo $film[0]['frame']; ?>"
          alt="Póster de <?php echo htmlspecialchars($film[0]['title']); ?>"
        >
      </div>

      <!-- INFORMACIÓN -->
      <div class="film-info">
        <ul class="film-details">
          <li><strong>ID:</strong> <?php echo $film[0]['idFilm']; ?></li>
          <li><strong>Género:</strong> <?php echo htmlspecialchars($film[0]['genre']); ?></li>
          <li><strong>Dirigida por:</strong> <?php echo htmlspecialchars($film[0]['directedBy']); ?></li>
          <li><strong>País:</strong> <?php echo htmlspecialchars($film[0]['origin_country']); ?></li>
          <li><strong>Idioma:</strong> <?php echo htmlspecialchars($film[0]['original_language']); ?></li>
          <li><strong>Duración:</strong> <?php echo $film[0]['duration']; ?> min</li>
          <li><strong>Reparto:</strong> <?php echo nl2br(htmlspecialchars($film[0]['castCrew'])); ?></li>
          <li><strong>Estreno:</strong> <?php echo date('d M Y', strtotime($film[0]['release_date'])); ?></li>
          <li><strong>Premios:</strong> <?php echo htmlspecialchars($film[0]['awards']); ?></li>
          <li><strong>Nominaciones:</strong> <?php echo htmlspecialchars($film[0]['nominations']); ?></li>
          <li><strong>Festivales:</strong> <?php echo htmlspecialchars($film[0]['festivals']); ?></li>
        </ul>

        <div class="film-overview">
          <h2>Sinopsis</h2>
          <p><?php echo htmlspecialchars($film[0]['overview']); ?></p>
        </div>

        <div class="film-ratings">
          <span class="rating-tag">Media: <?php echo $film[0]['vote_average']; ?></span>
          <span class="rating-tag">Tu voto: <?php echo $film[0]['individualRate']; ?></span>
          <span class="rating-tag">Global: <?php echo $film[0]['globalRate']; ?></span>
        </div>
      </div>
    </section>
  </div>
</body>

