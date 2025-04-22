<?php
require_once dirname(__DIR__) . '/config/config.globales.php';
require_once dirname(__DIR__) . '/api/comprobar.sesion.php';
require_once dirname(__DIR__) . '/class/class.post.php';

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = Post::getPostById($postId);

if (!$post) {
    echo "Post no encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once dirname(__DIR__) . '/header/header.php'; ?>
    <title><?php echo htmlspecialchars($post['title']); ?></title>
</head>
<body>

    <?php require_once dirname(__DIR__) . '/menu/menu.php'; ?>

    <main class="main pt-5">
        <div class="container">
            <article class="mb-5">
                <header class="mb-4 text-center">
                    <h1 class="display-5 fw-bold">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>
                    <img src="<?php echo htmlspecialchars($post['img'] ?: '../img/default_img_post.png'); ?>"
                         alt="Imagen del post"
                         class="img-fluid rounded shadow-sm my-4">
                </header>

                <div class="contenido-post">
                    <?php echo html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                </div>
            </article>
        </div>
    </main>
    <a href="generate.pdf.php?id=<?php echo $postId; ?>" class="btn btn-dark download-button">Descargar contenido</a>

</body>
</html>

