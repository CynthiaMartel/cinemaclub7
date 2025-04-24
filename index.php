<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require_once __DIR__.'/config/config.globales.php';
require_once __DIR__.'/api/comprobar.sesion.php';
require_once __DIR__ . '/modal.login.php';
require_once __DIR__ . '/modal.create.account.mail.php';
require_once __DIR__ . '/post/modal/create.editate.post.php';


global $actualUser;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo CONFIG_GENERAL['TITULO_WEB']; ?> - Acceso</title>
    <!-- Header Común a todas las páginas de la aplicación  -->
    <?php include_once(__DIR__ ).'/header/header.php'; ?>
</head>

    <body>
        <?php include_once(__DIR__ ). '/menu/menu.php'; ?> 


        <?php if ($actualSession->read('id') != null) { ?><div class="container mt-2 mb-2">
            <div class="row justify-content-center mb-auto">
                <div class="col-12 text-center text-light">
                    <h1>¡Te damos la bienvenida <?php echo ucwords(strtolower($actualUser->getName())); ?>!</h1>
                </div>
            </div>
        </div><?php }?>

        <!-- Texto sobre la imagen -->
        <div class="bg-cover d-flex flex-column justify-content-center align-items-center text-center mb-auto mt-2" 
            style="min-height: 100vh; background-size: cover; background-position: center; background-repeat: no-repeat;">
            
            <h1 style="color: #C62C0A; border-radius: 4px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);">Watch. Rate. Debate.</h1>
            <h3  style="color: #C62C0A; border-radius: 4px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);">The film lovers' community starts here</h3>
            <a href="#" class="btn btn-success mt-3" onclick="openModalNewAccount(); return false;">¡Únete a la comunidad!</a>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
     <!-- Footer -->
    <?php include_once(__DIR__.'/header/footer.php'); ?>
    <?php include_once(__DIR__ . '/../header/footer_bootstraptable.php'); ?>

    

</html>

