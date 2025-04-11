<?php

require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__.'/comprobar.sesion.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/../class/function.globales.php';
require_once __DIR__ . '/../class/class.Post.php';

/* @var User $userActual */
global $actualUser;

// Comprobamos que se ha accedido a esta api mediante POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    exit;
}

// Comprobamos que existe el campo tarea
$tarea = $_POST['tarea'] ?? null;
if (is_null($tarea)) {
    header('Content-Type: application/json');
    echo json_encode(['exito' => 0, 'mensaje' => 'No se ha recibido ninguna tarea']);
    exit;
}


$respuesta = array();
switch($tarea) {
    /* case 'UPLOAD_POST': //Cargar datos del Post
        $id = intval($_POST['id']);
        $post = new Post($id);
        if ($post->getId() == 0) {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'No se pudo cargar los datos del post';
            break;
        }

        $data['id'] = $post->getIdPost();
        $data['idUser'] = $post->getIdUser();
        $data['title'] = $post->getTitle();
        $data['content'] = $post->getContent();
        $data['visible'] = $post->getVisible();
        $data['editorName'] = $post->getEditorName();
       

        $respuesta['exito'] = 1;
        $respuesta['datos'] = $data;
        break;
 */
case 'SAVE_POST': // Guardar el Post
    $post = new Post(); // Se crea el post nuevo

    $title = sanitizarString(trim($_POST["title"]));
    $editorName = sanitizarString(trim($_POST["editorName"]));
    $content = sanitizarString(trim($_POST["content"]));

    if (strlen($title) == 0 || strlen($editorName) == 0) {
        $respuesta['exito'] = 0;
        $respuesta['errorTitleEditor'] = 1;
        $respuesta['mensaje'] = 'Debe rellenar los campos Título y Editor/Editora';
        break;
    }

    if (strlen($content) == 0) {
        $respuesta['exito'] = 0;
        $respuesta['errorContent'] = 1;
        $respuesta['mensaje'] = 'Debe rellenar el campo de contenido del Post';
        break;
    }
    case 'SAVE_POST': // Guardar el Post
    $post = new Post(); // Se crea el post nuevo

    $title = sanitizarString(trim($_POST["title"]));
    $editorName = sanitizarString(trim($_POST["editorName"]));
    $content = sanitizarString(trim($_POST["content"]));

    if (strlen($title) == 0 || strlen($editorName) == 0) {
        $respuesta['exito'] = 0;
        $respuesta['errorTitleEditor'] = 1;
        $respuesta['mensaje'] = 'Debe rellenar los campos Título y Editor/Editora';
        break;
    }

    if (strlen($content) == 0) {
        $respuesta['exito'] = 0;
        $respuesta['errorContent'] = 1;
        $respuesta['mensaje'] = 'Debe rellenar el campo de contenido del Post';
        break;
    }

    //if (!validarFecha($_POST['fechaHora'])) {
           // $respuesta['exito'] = 0;
            //$respuesta['errorFecha'] = 1;
            //$respuesta['mensaje'] = 'La fecha no es válida';
            //break;
        //}

        //$post->setFechaHora($_POST['fechaHora']);
    $post->setTitle($title);
    $post->setEditorName($editorName);
    $post->setContent($content);

    $userId = $actualSession->read('id');
    $post->setIdUser($userId);

    // Siempre guardar como visible = 0 por defecto
    //$post->setVisible(0);
    $post->setVisible(!($_POST["visible"] === 'false'));

    if ($post->save()) {
        $respuesta['exito'] = 1;
        $respuesta['id'] = $post->getIdPost();
        $respuesta['mensaje'] = 'Contenido del Post guardado con éxito. Post guardado';
    } else {
        $respuesta['exito'] = 0;
        $respuesta['mensaje'] = 'Ha ocurrido un error al intentar guardar el post';
    }
    break;



    case 'MAKE_VISIBLE':
        $idPost = intval($_POST['id']);
        $visible = isset($_POST['visible']) ? 1 : 0;

        $post = new Post($idPost);
    
        if ($post->getIdPost() == 0) {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Post no encontrado';
            break;
        }
        
        $post->setVisible($visible);

        if ($post-> save()) {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = 'Post actualizado como visible. Cargando...';
        } else {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Error al actualizar la visibilidad del post';
        }
        break;
        
    case 'DELETE_POST': //Borrar post
        $id = intval($_POST['id']);
        $post = new Post($id);
        if ($post->delete()) {
            $respuesta['exito'] = 1;
             $respuesta['mensaje'] = 'Post borrado con éxito';
        } else {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'No se ha podido eliminar el post';
        }
        break;

    
    default:
    $respuesta['exito'] = 0;
    $respuesta['mensaje'] = 'Error en la petición de gestión de Post';
    break;
}

ob_clean();
header('Content-Type: application/json');
echo json_encode($respuesta);