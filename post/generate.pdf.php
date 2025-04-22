<?php
require_once dirname(__DIR__) . '/config/config.globales.php';
require_once dirname(__DIR__) . '/class/class.post.php';
require_once dirname(__DIR__) . '/vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = Post::getPostById($postId);

if (!$post) {
    echo "Post no encontrado";
    exit;
}

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Construye el contenido HTML para el PDF
$html = '
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.6; }
        h1 { font-size: 22px; text-align: center; margin-bottom: 20px; }
        .contenido { font-size: 14px; }
    </style>
</head>
<body>
    <h1>' . htmlspecialchars($post['title']) . '</h1>
    <img src="' . htmlspecialchars($post['img'] ?: '../img/default_img_post.png') . '" alt="Imagen">
    <div class="contenido">' . html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</div>
</body>
</html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descarga directa
$dompdf->stream("post_" . $postId . ".pdf", ["Attachment" => true]);
exit;
