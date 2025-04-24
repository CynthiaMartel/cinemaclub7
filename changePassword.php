<?php
require_once __DIR__.'/config/config.globales.php';
require_once __DIR__.'/api/comprobar.sesion.php';

global $actualUser;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <?php include_once(__DIR__.'/header/header.php'); ?>
        
    </head>


<!--Form de cambio de contraseña-->
<body>
<?php include_once(__DIR__ . '/menu/menu.php'); ?> 
<div class="changePassword--page changePassword container-fluid vh-100 d-flex justify-content-center align-items-center">
    <div class="col-lg-6 col-12 p-4 rounded shadow" style="max-width: 400px; background-color:rgb(38, 37, 37); color: var(--text-color);">
        <h2 class="text-center mb-4" style="color: var(--text-color);">Cambiar Contraseña</h2>
        <form id="form-change-password">
            <div class="mb-3">
                <label for="form-actual-password" class="form-label">Contraseña actual</label>
                <input type="password" id="form-actual-password" class="form-control" name="passwordActual" placeholder="Introduzca su contraseña actual">
                <div class="mb-3" id="form-validarPasswordActual-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="form-password1" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" name="password1" id="form-password1" placeholder="Introduzca su nueva contraseña">
            </div>

            <div class="mb-3">
                <label for="form-password2" class="form-label">Repita nueva contraseña</label>
                <input type="password" class="form-control" name="password2" id="form-password2" placeholder="Introduzca otra vez su nueva contraseña">
            </div>

            <div class="mb-3">
                <span class="badge" id="general-form-cambiarPassword-feedback"></span>
            </div>

            <button type="submit" id="button-changePassword" class="btn w-100" style="background-color: var(--accent-color); color: white;">Cambiar contraseña</button>
        </form>
    </div>
</div>


<?php
    $userId = $actualSession->read('id');
    echo '<h1>'. $userId .'</h1>';
?>
        
</body>

<!-- Cambiar contraseña JS -->
<script src="./js/funciones.cambiarPassword.js"></script>

<script>
    document.getElementById("form-change-password").addEventListener("submit",
        function (event) {
            event.preventDefault();
            validarPasswordActual(event);
            cambiarPasswordUsuario(event);

        }
    );
</script>
</html>