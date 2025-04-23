<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';

if (empty($_POST['id'])) {
    echo json_encode(['error' => 'Falta el parámetro id.']);
    exit;
}

$id = intval($_POST['id']);
$db = new HandlerDB();

// 
$post = $db->getRecords(
    TABLE_POST,
    ['title','subtitle', 'editorName','visible','content', 'img'],
    'id = :id',
    [':id' => $id],
    null,
    'FETCH_ASSOC'
);

// Si hubo error de SQL, devuelve el mensaje para depurar
if ($post === false) {
    echo json_encode(['error' => $db->error]);
    exit;
}
if (count($post) === 0) {
    echo json_encode(['error' => 'No se encontró el post con id = '.$id.'.']);
    exit;
}

echo json_encode([
    'title'   => $post[0]['title'],
    'subtitle'   => $post[0]['subtitle'],
    'editor'  => $post[0]['editorName'], 
    'visible' => (bool)$post[0]['visible'],
    'content' => $post[0]['content'],
    'img'     => $post[0]['img']
]);
exit;



