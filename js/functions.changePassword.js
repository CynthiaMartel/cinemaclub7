function openModalChangePassword() {
    console.log("Entra en abrir modal");
    let input = $("#form-newAccount-password");

    // Limpiar formulario
    $(".form-change-password").removeClass("is-invalid");
    $("#form-change-password-feedback").removeClass("text-bg-danger text-bg-success").html('');
    $(".form-control").val('');

    // Mostrar modal
    $("#modal-changePassword").modal("show");
}

// Este bloque escucha el DOM y conecta el botón con la función
document.addEventListener("DOMContentLoaded", () => {
    $("#btn-open-modal-password").on("click", function(e) {
        e.preventDefault();
        openModalChangePassword();
        console.log("Entra en click de abrir modal")
    });
});


$(document).ready(function(){
    $('[data-bs-toggle="popover"]').popover();
});

function validarPasswordActual(event){
    const form = event.target;
    console.log("Entra en funcion validadPasswordActual")

    $(".form-control").removeClass("is-invalid is-valid");
    $("#form-actual-password").removeClass("is-invalid is-valid");

    $("#form-password1, #form-password2").removeClass("is-invalid is-valid");
    $("#general-form-cambiarPassword-feedback").removeClass("text-bg-danger text-bg-success");
    $("#general-form-cambiarPassword-feedback").html('');
    

    let formData = new FormData(form);
    formData.append('tarea', 'CHECK_PASSWORD_ACTUAL');
    formData.append('passwordActual', $("#form-actual-password").val());
    

    $.ajax({
        url: RUTA_URL_API + '/api.cambiarPassword.php',
        method: 'POST',
        data: formData, // Enviar el objeto FormData
        contentType: false, // No establecer el encabezado Content-Type manualmente
        processData: false, // No procesar los datos (necesario para FormData)

        success: function (respuesta) {
            
                    if (respuesta.exito === 0) {
                        console.log(respuesta)
                        $("#form-actual-password").addClass("is-invalid");
                        $("#form-validarPasswordActual-feedback").addClass("invalid-feedback text-danger").text(respuesta.mensaje);
                        
                    }

                    if (respuesta.exito === 1 ) {
                        console.log(respuesta)
                        $("#form-actual-password").removeClass("is-invalid").addClass("is-valid");
                        $("#form-validarPasswordActual-feedback").removeClass("is-invalid text-danger").addClass("valid-feedback text-success").text(respuesta.mensaje);
                    }
                }
        ,

        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
            alert('Ocurrió un error en la petición de cambio de contraseña');
        }
    });
    
}


function cambiarPasswordUsuario(event) {
    event.preventDefault(); // Evita que se envíe el formulario automáticamente
    const form = event.target;

    $("#form-password1, #form-password2").removeClass("is-invalid is-valid");
    $("#general-form-cambiarPassword-feedback").removeClass("text-bg-danger text-bg-success d-block d-none");
    $("#general-form-cambiarPassword-feedback").html('');


    let formData = new FormData(form);
    formData.append('tarea', 'CAMBIAR_PASSWORD');
    
    console.log("Datos enviados:", [...formData]);

    $.ajax({
        url: RUTA_URL_API + '/api.cambiarPassword.php',
        method: 'POST',
        data: formData, // Enviar el objeto FormData
        contentType: false, // No establecer el encabezado Content-Type manualmente
        processData: false, // No procesar los datos (necesario para FormData)

        success: function (respuesta) {
            $("#form-password1, #form-password2").removeClass("is-invalid is-valid");
            $("#general-form-cambiarPassword-feedback").removeClass("text-bg-danger text-bg-success d-block d-none");

                    if (respuesta.exito === 0) {
                        console.log(respuesta)
                        $("#form-password1").addClass("is-invalid");
                        $("#form-password2").addClass("is-invalid");

                        $("#general-form-cambiarPassword-feedback").addClass("text-bg-danger d-block");
                        $("#general-form-cambiarPassword-feedback").text(respuesta.mensaje);
                    

                    }else if (respuesta.exito === 2){
                       console.log(respuesta)
                       let input = $("#form-password1");
                       $("#form-password1").addClass("is-invalid");
                       $("#form-password2").addClass("is-invalid");

                       $("#general-form-cambiarPassword-feedback").addClass("text-bg-danger d-block");
                       $("#general-form-cambiarPassword-feedback").text(respuesta.mensaje);

                       input.popover("dispose"); // Elimina cualquier popover previo
                       input.popover({
                        title: "Requisitos de Contraseña",
                        content: "Debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.",
                        placement: "right",
                        trigger:"manual",
                        html: true,
                       })
                       input.popover("show");

                    }else if (respuesta.exito === 1) {
                    console.log(respuesta);
        
                    let input = $("#form-password1");
                    input.popover("disable");
                    $("#form-password1").removeClass("is-invalid").addClass("is-valid");
                    $("#form-password2").removeClass("is-invalid").addClass("is-valid");
                    
                    
                    $("#general-form-cambiarPassword-feedback").addClass("text-bg-success d-block");
                    $("#general-form-cambiarPassword-feedback").text(respuesta.mensaje);
                }
                    
                    
                }
        ,

        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
            alert('Ocurrió un error en la petición de cambio de contraseña');
        }
    });
}