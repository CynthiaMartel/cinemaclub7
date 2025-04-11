<!---Modal para logearte--->
<?php
require_once __DIR__ . '/config/config.globales.php';
;
?> 

<!DOCTYPE html>
<html lang="es">
    <head>
        <?php include_once(__DIR__.'/header/header.php'); ?>
        <?php include_once(__DIR__ . '/header/header_bootstraptable.php'); ?>
    </head>

<div class="modal fade" id="modal-login" tabindex="-1" aria-labelledby="exampleModalLabel" style="z-index:9999">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">
                    <i class="bi bi-film text-light me-2"></i> ¡Qué alegría tenerte de vuelta!
                </h1>   
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-dark-subtle">
                <div class="row">
                    <div class="col-12">
                        <form id="form-login">
                            <input type="hidden" id="form-login-id" name="id" value="0">

                            <div class="row">
                                <!-- Email -->
                                <div class="col-12 col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="form-login-email" name="user" placeholder="Email">
                                        <label for="form-login-email">Email</label>
                                    </div>
                                </div>

                                <!-- Contraseña -->
                                <div class="col-12 col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="form-login-password" name="password" placeholder="Contraseña">
                                        <label for="form-login-password">Contraseña</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Feedback -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="mb-3">
                                        <span class="badge" id="form-login-feedback"></span> 
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botón tipo submit que al clickar lleva la función enviarFormulario -->
                            <div class="bg-dark-subtle d-flex justify-content-end align-items-center gap-2">
                                <div class="spinner-border text-success" id="spinner" role="status" style="display: none; width: 1.5rem; height: 1.5rem;">
                                <span class="visually-hidden">Cargando...</span>
                                </div>
                                <button type="submit" class="btn btn-success text-lg fw-bold">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>

<script>
    document.getElementById("form-login").addEventListener("submit",
        function (event) {
            event.preventDefault();
            enviarFormularioLogin(event);
        }
    );
</script>



