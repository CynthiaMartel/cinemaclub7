/* function handleCredentialResponse(response) {
    fetch('google-signin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ credential: response.credential })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'dashboard.php'; // Redirige a tu app
        } else {
            alert("Error al registrarse: " + data.error);
        }
    });
} */
    