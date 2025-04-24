<?php
require_once dirname(__DIR__) . '/config/config.globales.php';
require_once dirname(__DIR__) . '/api/comprobar.sesion.php';
require_once dirname(__DIR__) . '/class/class.post.php';
require_once dirname(__DIR__). '/modal.login.php';

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
<title><?php echo CONFIG_GENERAL['TITULO_WEB']; ?> -Leer Noticia</title>
    <?php require_once dirname(__DIR__) . '/header/header.php'; ?>
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <!-- Enlazar estilos específicos de lectura de post -->
    <link rel="stylesheet" href="../assets/css/readPost.css">
</head>
<body class="read-post read-post--page">

    <?php require_once dirname(__DIR__) . '/menu/menu.php'; ?>

    <main class="main read-post__main pt-5">
        <div class="read-post__container">
            <article class="read-post__article mb-5">
                <header class="read-post__header mb-4 text-center">
                    <h1 class="read-post__title display-5 fw-bold">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>
                    <img src="<?php echo htmlspecialchars($post['img'] ?: '../img/default_img_post.png'); ?>"
                         alt="Imagen del post"
                         class="read-post__image img-fluid rounded shadow-sm my-4">
                </header>

                <div class="read-post__content contenido-post">
                    <?php echo html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                </div>
            </article>
            <a href="generate.pdf.php?id=<?php echo $postId; ?>"
               class="read-post__download-button btn btn-dark download-button">
                Descargar contenido
            </a>
        </div>
    </main>

    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>

