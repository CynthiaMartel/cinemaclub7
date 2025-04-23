document.getElementById("voteForm").onsubmit = function(e) {
    e.preventDefault();
  
    const formData = new FormData(this);
  
    fetch('vote.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Mensaje de éxito
        alert(data.message);
  
        // Actualiza la puntuación individual del usuario
        document.getElementById('user-rating').innerText = data.individualRate;
  
        // Actualiza la puntuación global con 1 decimal
        document.getElementById('global-rating').innerText = parseFloat(data.globalRate).toFixed(1);
      } else {
        // Mensaje de error
        alert(data.message);
      }
    })
    .catch(error => console.error('Error:', error));
  };
  