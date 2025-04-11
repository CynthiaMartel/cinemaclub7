function openModalLogin() {
    $(".form-control").removeClass("is-invalid");
    $("#form-login-feedback").removeClass("text-bg-danger text-bg-success").html('');

    $(".form-control").val('');
    $("#modal-login").modal("show");
}

function enviarFormularioLogin(event) {
    console.log("LLegó");
    const form = event.target;

    $(".form-control").removeClass("is-invalid is-valid");
    $("#form-login-feedback").removeClass("text-bg-danger text-bg-success");
    $("#form-login-feedback").html('');

    let formData = new FormData(form);
    formData.append('tarea', 'VALIDATE_LOGIN');

    // Mostrar el spinner al hacer clic en "Login"
    $("#spinner").show();

    $.ajax({
        url: RUTA_URL_API + '/api.login.php',
        method: 'POST',
        data: formData, // Enviar el objeto FormData
        contentType: false, // No establecer el encabezado Content-Type manualmente
        processData: false, // No procesar los datos (necesario para FormData)

        success: function (respuesta) {
                    if (respuesta.exito === 0) {
                        $("#form-login-email").addClass("is-invalid");
                        $("#form-login-password").addClass("is-invalid");
                        $("#form-login-feedback").addClass("text-bg-danger");
                        $("#spinner").hide(); // Ocultar el spinner
                        $("#form-login-feedback").text(respuesta.mensaje);
                    }

                    if (respuesta.exito === 1) {
;
                        $("#form-login-email").removeClass("is-invalid").addClass("is-valid");
                        $("#form-login-password").removeClass("is-invalid").addClass("is-valid");
                        $("#form-login-feedback").removeClass("is-invalid text-danger").addClass("valid-feedback text-success").text(respuesta.mensaje);
                        
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