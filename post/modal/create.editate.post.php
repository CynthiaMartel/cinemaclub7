<!-- Modal para crear y editar Post de noticias, con editor de texto enrrquecido (CKEditor) -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <!-- Tamaño fijo del editor de texto enrriquecido -->
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>
</head>
<body>
    <div class="modal fade" id="modal-create-editate-post" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="bi bi-bandaid"></i> Añadir/Editar Post
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-create-editate-post">
                        <input type="hidden" id="form-create-editate-post-id" name="id" value="">

                        <!-- Campos de entrada -->
                        <div class="row mb-3">
                            <div class="col-12 col-lg-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="form-create-editate-post-title" name="title" placeholder="Título">
                                    <label for="form-create-editate-post-title">Título</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="form-create-editate-post-editor" name="editorName" placeholder="Autor/Autora">
                                    <label for="form-create-editate-post-editor">Autor/Autora</label>
                                </div>
                            </div>
                        </div>

                        <!-- Campo de contenido con CKEditor -->
                        <div class="mb-3">
                            <label for="form-create-editate-post-content">Contenido de noticia</label>
                            <textarea class="form-control" id="form-create-editate-post-content" name="content" placeholder="¡Empieza a escribir aquí!"></textarea>
                        </div>

                        <!-- Switch guardar como borrador -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="form-create-editate-post-visible" name="visible">
                            <label class="form-check-label" for="form-create-editate-post-visible">Visible</label>
                        </div>

                        <!-- Feedback -->
                        <div class="text-center">
                            <span class="badge" id="form-create-editate-post-feedback"></span>
                        </div>

                        <!-- Botón submit -->
                        <div class="bg-dark-subtle d-flex justify-content-end align-items-center gap-2">
                            <div class="spinner-border text-success" id="spinner" role="status" style="display: none; width: 1.5rem; height: 1.5rem;">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <button type="submit" class="btn btn-success text-lg fw-bold" id="modal-btn-savePost">Guardar Post</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script CKEditor -->
    <script>
        let editorInstance;

        ClassicEditor
            .create(document.querySelector('#form-create-editate-post-content'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error('Error al inicializar CKEditor:', error);
            });

        // Evitar que se cierre el modal al interactuar con el editor (normalmente no es necesario, pero por si acaso)
        document.querySelector('#modal-create-editate-post').addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // Captura del submit del formulario
        document.getElementById("form-create-editate-post").addEventListener("submit", function (event) {
            event.preventDefault();

            // Obtener el contenido del editor y actualizar el textarea real por si lo necesitas en el back
            document.getElementById("form-create-editate-post-content").value = editorInstance.getData();

            // Aquí llamarías tu función para guardar el post
            savePost(event);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>










