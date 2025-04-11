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

<!doctype html>
<html lang="es">
    <head>
        <title><?php echo CONFIG_GENERAL['TITULO_WEB']; ?> - Acceso</title>
        <!-- Header Común a todas las páginas de la aplicación  -->
        <?php include_once(__DIR__ . '/header/header_bootstraptable.php'); ?>
        <?php include_once(__DIR__.'/header/header.php'); ?>
    </head>

    <body>
        <?php include(__DIR__.'/menu/menu.php'); ?>

        <?php if ($actualSession->read('id') != null) { ?><div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-12 text-center text-light">
                    <h1>¡Te damos la bienvenida <?php echo ucwords(strtolower($actualUser->getName())); ?>!</h1>
                </div>
            </div>
        </div><?php }?>

        <!-- Texto sobre la imagen -->
        <div class="bg-cover d-flex flex-column justify-content-center align-items-center text-center" 
            style="min-height: 100vh; background-image: url('./imagenes/fondo.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            
            <h1 class="text-light">Watch. Rate. Debate.</h1>
            <h3 class="text-light">The film lovers' community starts here</h3>
            <a href="#" class="btn btn-success mt-3" onclick="openModalNewAccount(); return false;">¡Únete a la comunidad!</a>
        </div>
            <!-- <a href="#" class="btn btn-success mt-3" id="customGoogleSignUpBtn">¡Únete a la comunidad!</a> -->

            <!-- Google Sign-up (oculto) -->
            <!-- <div style="display:none;">
                <div id="g_id_onload"
                    data-client_id="354391895094-j9s20jbqrj4ss539f2uj02t69g5d01v2.apps.googleusercontent.com"
                    data-context="signup"
                    data-ux_mode="popup"
                    data-callback="handleCredentialResponse"
                    data-auto_prompt="false">
                </div>

                <div class="g_id_signin"
                    data-type="standard"
                    data-shape="pill"
                    data-theme="outline"
                    data-text="sign_in_with"
                    data-size="large">
                </div>
            </div>
        </div> -->

    </body>
     <!-- Footer -->
    <?php include_once(__DIR__.'/header/footer.php'); ?>
    <?php include_once(__DIR__ . '/../header/footer_bootstraptable.php'); ?>

    <!-- Script de google para crear una nueva cuenta de usuario -->
    <!-- <script src="https://accounts.google.com/gsi/client" async defer></script>

    <script src="singUp-mail/singUp.functions.js"> </script>

    <script>document.getElementById('customGoogleSignUpBtn').addEventListener('click', function () {
        google.accounts.id.prompt(); // Esto abre el popup de Google
    });</script> -->

</html>

