function openModalPost(post_id = null) {
    $(".form-control").removeClass("is-invalid is-valid");

    $("#form-create-editate-post-feedback")
        .removeClass("text-bg-danger text-bg-success valid-feedback invalid-feedback")
        .html('');

    let title = ''
    let editor = ''
    let visible = false
    let content = ''

    if(post_id != null){
        //FUNCION PARA TRAER LOS DATOS DEL POST INDIVIDUAL
        title = 'Queso se meó'
        editor = 'Sin'
        visible = false
        content = 'jdfghdfghdlfgdfklgjlsiyeifjeilfhefuegfukhfukgeug'

        $("#form-create-editate-post-id").val(post_id);

        $("#modal-btn-savePost").text('Actualizar Post');
        $("#modal-btn-savePost").addClass('btn-warning');
    }else{
        $("#modal-btn-savePost").text('Guardar Post');
        $("#modal-btn-savePost").removeClass('btn-warning');
    }

    $("#form-create-editate-post-id").val(post_id);
    $("#form-create-editate-post-title").val(title);
    $("#form-create-editate-post-editor").val(editor);
    $("#form-create-editate-post-visible").prop('checked', visible);
    if (editorInstance) {
        editorInstance.setData(content);
    }

    $("#modal-create-editate-post").modal("show");
}

$(document).ready(function () {
    $('#modal-create-editate-post').on('shown.bs.modal', function () {
        let input = $("#form-create-editate-post-visible"); 

        input.popover("dispose");

        input.popover({
            title: "¿Deseas guardar como visibile?",
            content: "Primero marca esta casilla y después pulsa el botón 'Guardar Post' para que esta noticia sea publicada y otros usuarios puedan leerla. Si por el contrario, solo quieres guardarla como borrador, pulsa el botón 'Guardar Post' y deja el botón 'Visible' desmarcado.",
            placement: "bottom",
            trigger: "manual",
            html: true,
            container: 'body',
            customClass: "popover-small"
        });

        input.popover("show");
    });
});

function savePost(event) {
    console.log("Entra a savePost");
    event.preventDefault();
    const form = event.target;

    $(".form-control").removeClass("is-invalid is-valid");
    $("#form-create-editate-post-feedback").removeClass("text-bg-danger text-bg-success").html('');
    $("#spinner").show();

    let formData = new FormData(form);
    formData.append('tarea', 'SAVE_POST');
    formData.set('visible', $("#form-create-editate-post-visible").prop('checked'));

    $.ajax({
        url: RUTA_URL_API + '/api.post.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,

        success: function (respuesta) {
            if (respuesta.exito === 1) {
                $("#form-create-editate-post-title, #form-create-editate-post-editor, #form-create-editate-post-content").removeClass("is-invalid").addClass("is-valid");
            
                $("#form-create-editate-post-feedback").addClass("valid-feedback text-success").text(respuesta.mensaje);
            
                const idPost = respuesta.id;
                const isVisible = $("#form-create-editate-post-visible").prop("checked");
            
                if (isVisible) {
                    VisiblePost(idPost);
                } else {
                    setTimeout(() => {
                        alert("Post guardado como borrador.");
                        $("#spinner").show();
                        window.location.href = RUTA_URL_PRINCIPAL + '/post/index.php';
                    }, 2000);
                }
            }
        },

        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
            alert('Ocurrió un error al cargar el post');
            $("#spinner").hide();
        }
    });
}

function VisiblePost(idPost) {
    const formData = new FormData();
    formData.append('tarea', 'MAKE_VISIBLE');
    formData.append('id', idPost);
    formData.append('visible', '1');

    $("#spinner").show();

    $.ajax({
        url: RUTA_URL_API + '/api.post.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            if (respuesta.exito === 1) {
                $("#spinner").show();
                setTimeout(() => {
                    window.location.href = RUTA_URL_PRINCIPAL + '/post/index.php';
                }, 2000);
            } else {
                alert("No se pudo hacer visible el post: " + respuesta.mensaje);
            }
        },
        error: function () {
            alert('Error al intentar hacer visible el post');
        }
    });
}

function confirmDeletePost(id) {
    if (!confirm("¿quieres borrar este post? Esta acción no se puede deshacer.")) {
        return;
    }

    const formData = new FormData();
    formData.append('tarea', 'DELETE_POST');
    formData.append('id', id);

    $("#spinner").show();

    $.ajax({
        url: RUTA_URL_API + '/api.post.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            $("#spinner").hide();
            if (respuesta.exito === 1) {
                alert(respuesta.mensaje);
                window.location.href = RUTA_URL_PRINCIPAL + '/post/index.php'; // o recargar página
            } else {
                alert("Error al borrar el post: " + respuesta.mensaje);
            }
        },
        error: function () {
            $("#spinner").hide();
            alert("Hubo un error al intentar borrar el post.");
        }
    });
}

function uploadPost(boton) {  
    const form = $("#form-create-editate-post").get(0);
    $(".form-control").removeClass("is-invalid");
    $("#form-create-editate-post-feedback").removeClass("text-bg-danger text-bg-success").html('');

    if (window.editor) {
        $("#form-create-editate-post-content").val(window.editor.getData());
    }

    if ($("#form-create-editate-post-content").val().trim() === "") {
        $("#form-create-editate-post-feedback").addClass('text-bg-danger').html("Debe rellenar el campo de contenido del Post");
        $("#form-create-editate-post-content").addClass('is-invalid');
        return;
    }

    let formData = new FormData(form);
    formData.set('content', $("#form-create-editate-post-content").val());
    formData.append('tarea', 'SAVE_POST');
    formData.append('id', $("#form-create-editate-post-id").val());

    $.ajax({
        url: RUTA_URL_API + '/api.post.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,

        success: function (respuesta) {
            console.log("Respuesta del servidor (SAVE_POST):", respuesta);

            if (respuesta.exito === 0) {
                $("#form-create-editate-post-feedback").addClass('text-bg-danger').html(respuesta.mensaje);

                if (respuesta.errorTitleEditor === 1) {
                    $("#form-create-editate-post-title").addClass('is-invalid');
                    $("#form-create-editate-post-editor").addClass('is-invalid');
                }
                if (respuesta.errorContent === 1) {
                    $("#form-create-editate-post-content").addClass('is-invalid');
                }
                return;
            }

            if (respuesta.exito === 1) {
                $("#form-create-editate-post-title, #form-create-editate-post-editor, #form-create-editate-post-content")
                    .removeClass("is-invalid").addClass("is-valid");

                $("#form-create-editate-post-feedback")
                    .removeClass("is-invalid text-danger")
                    .addClass("valid-feedback text-success")
                    .text(respuesta.mensaje);

                setTimeout(() => {
                    window.location.href = RUTA_URL_PRINCIPAL + '/index.php';
                }, 2000);
            }
        },

        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
            alert('Ocurrió un error al enviar el formulario');
        }
    });
}
