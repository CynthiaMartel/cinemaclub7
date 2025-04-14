function openModalNewAccount() {
    let input = $("#form-newAccount-password");

    // Limpiar formulario
    $(".form-newAccount").removeClass("is-invalid");
    $("#form-newAccount-feedback").removeClass("text-bg-danger text-bg-success").html('');
    $(".form-control").val('');

    // Mostrar modal
    console.log($("#spinner").length);
    $("#modal-newAccount").modal("show");
}

$(document).ready(function () {
    $('#modal-newAccount').on('shown.bs.modal', function () {
        let input = $("#button-createAccount"); 

        // Eliminar cualquier popover previo
        input.popover("dispose");

        // Crear popover
        input.popover({
            title: "Requisitos de Contraseña",
            content: "Debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.",
            placement: "bottom",
            trigger: "manual",
            html: true,
            container: 'body',
            customClass: "popover-small"
        });

        // Mostrar popover
        input.popover("show");
    });
});



function enviarFormularioNewAccount(event) {
    console.log("Entra a función enviarFormularioLogin");
    const form = event.target;

    $(".form-control").removeClass("is-invalid is-valid");
    $("#form-newAccount-feedback").removeClass("text-bg-danger text-bg-success");
    $("#form-newAccount-feedback").html('');

    let formData = new FormData(form);
    formData.append('tarea', 'CREATE_ACCOUNT');

    // Mostrar el spinner al hacer clic en "Login"
    $("#spinner").show();

    $.ajax({
        url: RUTA_URL_API + '/api.createAccount.mail.php',
        method: 'POST',
        data: formData, // Enviar el objeto FormData
        contentType: false, // No establecer el encabezado Content-Type manualmente
        processData: false, // No procesar los datos (necesario para FormData)
        
        success: function (respuesta) {
            console.log("Entra en ajax y en funcion respuesta")

                    if (respuesta.exito === 0) {
                        $("#form-newAccount-name").addClass("is-invalid");
                        $("#form-newAccount-email").addClass("is-invalid");
                        $("#form-newAccount-password").addClass("is-invalid");
                        $("#form-newAccount-repeatedPassword").addClass("is-invalid");

                        $("#form-newAccount-feedback").addClass("text-bg-danger");
                        $("#spinner").hide(); // Ocultar el spinner
                        $("#form-newAccount-feedback").text(respuesta.mensaje);

                    }

                    if (respuesta.exito === 1) {
                        $("#form-newAccount-name").removeClass("is-invalid").addClass("is-valid");
                        $("#form-newAccount-email").removeClass("is-invalid").addClass("is-valid");
                        $("#form-newAccount-password").removeClass("is-invalid").addClass("is-valid");
                        $("#form-newAccount-repeatedPassword").removeClass("is-invalid").addClass("is-valid");

                        $("#form-newAccount-feedback").removeClass("is-invalid text-danger").addClass("valid-feedback text-success").text(respuesta.mensaje);

                        // Espera 3 segundos antes de redirigir
                        setTimeout(() => {
                            window.location.href = RUTA_URL_PRINCIPAL + '/index.php';
                        }, 3000);

                       //ENVIAR UN MENSAJE por GMAIL DE AUTENTIFICACIÓN PARA LOGUEARTE */

                    }
                },
        

        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
            alert('Ocurrió un error al enviar el formulario');
        }
    });
}