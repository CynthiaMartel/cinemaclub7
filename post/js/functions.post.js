function openModalPost() {
    $(".form-control").removeClass("is-invalid");
    $("#form-create-editate-post-feedback").removeClass("text-bg-danger text-bg-success").html('');

    $(".form-control").val('');
    $("#modal-create-editate-post").modal("show");
}

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
                $("#form-create-editate-post-title, #form-create-editate-post-editor, #form-create-editate-post-content").removeClass("is-invalid") .addClass("is-valid");
            
                $("#form-create-editate-post-feedback").addClass("valid-feedback text-success").text(respuesta.mensaje);
            
                const idPost = respuesta.id;
            
                const isVisible = $("#form-create-editate-post-visible").prop("checked");
            
                if (isVisible) {
                    // Si el switch estaba activado, hacer visible el post directamente
                    VisiblePost(idPost);
                } else {
                    // Si no, mostrar un mensaje opcional o dejarlo como borrador
                    $("#spinner").hide(); // ocultamos spinner si no redirige
                    alert("Post guardado como borrador.");
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
                window.location.href = RUTA_URL_PRINCIPAL + '/post/index.php';
            } else {
                alert("No se pudo hacer visible el post: " + respuesta.mensaje);
            }
        },
        error: function () {
            alert('Error al intentar hacer visible el post');
        }
    });
} 


function uploadPost(boton) {  
    const form = $("#form-create-editate-post").get(0);

    // Eliminar clases de error previas
    $(".form-control").removeClass("is-invalid");
    $("#form-create-editate-post-feedback").removeClass("text-bg-danger text-bg-success");
    $("form-create-editate-post").html('');

    // Asignar manualmente el contenido del editor al campo content
    if (window.editor) {
        $("#form-create-editate-post-content").val(window.editor.getData());
    }
    
    // Verificar si el contenido es válido antes de enviar
    if ($("#form-create-editate-post-content").val().trim() === "") {
        $("#form-create-editate-post-feedback").addClass('text-bg-danger').html("Debe rellenar el campo de contenido del Post");
        $("#form-create-editate-post-content").addClass('is-invalid');
        return;
    }
    
    // Crear objeto FormData con el formulario actualizado
    let formData = new FormData(form);

    // Recoger el estado del checkbox de borrador
    /* const visible = $("#form-create-editate-post-visible").prop("checked") ? 1 : 0;
    formData.append('visible', visible); */

    // Asegurar que el contenido del editor se envíe correctamente
    formData.set('content', $("#form-create-editate-post-content").val());

    // Agregar la tarea
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
                $("#form-create-editate-post-feedback").addClass('text-bg-danger');
                $("#form-create-editate-post-feedback").html(respuesta.mensaje);

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
                $("#form-create-editate-post-title").removeClass("is-invalid").addClass("is-valid");
                $("#form-create-editate-post-editor").removeClass("is-invalid").addClass("is-valid");
                $("#form-create-editate-post-content").removeClass("is-invalid").addClass("is-valid");

                $("#form-create-editate-post-feedback").removeClass("is-invalid text-danger").addClass("valid-feedback text-success").text(respuesta.mensaje);
                
                // Espera 3 segundos antes de redirigir
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