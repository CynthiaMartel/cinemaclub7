<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once  dirname(__DIR__). '/class/class.User.php';


// 1) Cargar y validar sesión + usuario
require_once dirname(__DIR__).'/api/comprobar.sesion.php';
// (comprobar.sesion.php arranca la sesión y al final hace:
//    $actualUser = new User( $actualSession->read('id') ); )

// forzar el scope global si quisieras (aunque en un script suelto no hace falta)
global $actualUser;

if (!isset($actualUser) || !$actualUser->getidUser()) {
    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión para votar.'
    ]);
    exit;
}
$idUser = $actualUser->getidUser();

// 2) Recoger y validar POST (escala de 0 a 10 puntos)
$filmId = filter_input(INPUT_POST, 'film_id', FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

// Ahora permitimos valores entre 0 y 10
if (!$filmId || $rating === false || $rating < 0 || $rating > 10) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de película o calificación inválida. Debe ser entre 0 y 10.'
    ]);
    exit;
}

// 3) Abrir conexión y arrancar transacción
require_once dirname(__DIR__).'/db/class.HandlerDB.php';
$gestorDB = new HandlerDB();
$gestorDB->dbh->beginTransaction();

try {
    // 4) ¿Ya existe un voto de este usuario para esta película?
    $existe = $gestorDB->getRecords(
        TABLE_INDIVIDUAL_RATE,
        ['1'],
        'idUser = :u AND idFilm = :f',
        [':u' => $idUser, ':f' => $filmId],
        null,
        'FETCH_ASSOC'
    );

    if ($existe) {
        // actualizarRegistro(tabla, datos a SET, claves primarias)
        $gestorDB->actualizarRegistro(
            TABLE_INDIVIDUAL_RATE,
            ['rate' => $rating],
            ['idUser' => $idUser, 'idFilm' => $filmId]
        );
    } else {
        // insertarRegistro(tabla, datos, campos autonuméricos a omitir)
        $gestorDB->insertarRegistro(
            TABLE_INDIVIDUAL_RATE,
            ['rate' => $rating, 'idUser' => $idUser, 'idFilm' => $filmId],
            []  // no hay campo autonumérico que omitir
        );
    }

    // 5) Recalcular la media global
    $res = $gestorDB->getRecords(
        TABLE_INDIVIDUAL_RATE,
        ['AVG(rate) AS average'],
        'idFilm = :f',
        [':f' => $filmId],
        null,
        'FETCH_ASSOC'
    );
    $avg = $res[0]['average'] ?? 0.0;
    $gestorDB->actualizarRegistro(
        TABLE_FILM,
        ['globalRate' => $avg],
        ['idFilm' => $filmId]
    );

    // 6) Confirmar
    $gestorDB->dbh->commit();

    echo json_encode([
        'success'        => true,
        'message'        => 'Voto registrado correctamente.',
        'individualRate' => $rating,             // voto individual para usuario logueado que ha votado esa película
        'globalRate'     => round($avg, 1)       // media redondeada a 1 decimal
    ]);

} catch (Exception $e) {
    $gestorDB->dbh->rollBack();
    // opcional: log($e->getMessage()) en FICHERO_LOG_DB
    echo json_encode([
        'success' => false,
        'message' => 'Error interno al procesar el voto.'
    ]);
}

