<!---Modal para crear nueva cuenta--->
<?php
require_once __DIR__ . '/config/config.globales.php';
;
?> 

<div class="modal fade" id="modal-newAccount" tabindex="-1" aria-labelledby="exampleModalLabel" style="z-index:9999">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">
                    <i class="bi bi-camera-reels me-2"></i> ¡Únete la comunidad!
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-dark-subtle">
                <div class="row">
                    <div class="col-12">
                        <form id="form-newAccount">
                            <input type="hidden" id="form-newAccount-id" name="id" value="0">

                            <div class="row">
                                <!-- Name -->
                                <div class="col-12 col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="form-newAccount-name" name="name" placeholder="¿Cómo te llamas?">
                                        <label for="form-newAccount-name">Usuario</label>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-12 col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="form-newAccount-email" name="email" placeholder="Email">
                                        <label for="form-newAccount-email">Email</label>
                                    </div>
                                </div>
                            </div>

                              <!-- Password -->
                            <div class="row">
                              <div class="col-12 col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="form-newAccount-password" name="password" placeholder="Contraseña">
                                        <label for="form-newAccount-password">Contraseña</label>
                                    </div>
                                </div>
                                 <!-- repeatedPassword-->
                                <div class="col-12 col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="form-newAccount-repeatedPassword" name="repeatedPassword" placeholder="Contraseña">
                                        <label for="form-newAccount-repeatedPassword">Repite la contraseña</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Feedback -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="mb-3">
                                        <span class="badge" id="form-newAccount-feedback"></span> 
                                    </div>
                                </div>
                            </div>

                            <!-- Botón tipo submit que al clickar lleva la función enviarFormulario -->
                            <div class="bg-dark-subtle d-flex justify-content-end align-items-center gap-2">
                                <div class="spinner-border text-success" id="spinner" role="status" style="display: none; width: 1.5rem; height: 1.5rem;">
                                <span class="visually-hidden">Cargando...</span>
                                </div>
                                <button type="submit" id="button-createAccount" class="btn btn-success text-lg fw-bold">Crear cuenta</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>

<script>
    document.getElementById("form-newAccount").addEventListener("submit",
        function (event) {
            event.preventDefault();
            enviarFormularioNewAccount(event);
        }
    );
</script>
