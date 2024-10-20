<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Calificación</title>
    <link rel="stylesheet" href="assets/css/estilo.css">
    <link rel="stylesheet" href="../css/comentario_cliente.css">
</head>
<body>
    <button class="open-modal-btn">Dejar comentario</button>

    <!-- Ventana modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Califica el Servicio</h2>

            <form id="ratingForm" action="guardar_calificacion.php" method="POST">
                <label for="comentario">Deja tu comentario:</label><br>
                <textarea id="comentario" name="comentario" rows="5" cols="30" required></textarea><br>

                <label for="calificacion">Calificación:</label><br>
                <div class="stars" id="stars">
                    <i data-value="1" class="star">&#9733;</i>
                    <i data-value="2" class="star">&#9733;</i>
                    <i data-value="3" class="star">&#9733;</i>
                    <i data-value="4" class="star">&#9733;</i>
                    <i data-value="5" class="star">&#9733;</i>
                </div>
                <input type="hidden" id="calificacion" name="calificacion" value="0" required>

                <label for="servicio">Selecciona un servicio:</label><br>
                <select id="servicio" name="servicio" required>
                    <option value="">Selecciona un servicio</option>
                </select><br>

                <button type="submit">Enviar Calificación</button>
            </form>
        </div>
    </div>

    <script>
        // Abrir y cerrar el modal
        const modal = document.getElementById('modal');
        const openModalBtn = document.querySelector('.open-modal-btn');
        const closeModalBtn = document.querySelector('.close-modal');

        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Lógica de las estrellas
        const stars = document.querySelectorAll('.star');
        const calificacionInput = document.getElementById('calificacion');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-value');
                calificacionInput.value = rating;
                stars.forEach(s => s.classList.remove('filled'));
                for (let i = 0; i < rating; i++) {
                    stars[i].classList.add('filled');
                }
            });
        });

        // Cargar los servicios desde la base de datos
        document.addEventListener('DOMContentLoaded', function() {
            fetch('cargar_servicios_calificacion.php')
                .then(response => response.json())
                .then(data => {
                    const servicioSelect = document.getElementById('servicio');
                    data.forEach(servicio => {
                        const option = document.createElement('option');
                        option.value = servicio.codigo; // Asegúrate de que esto es el ID correcto
                        option.textContent = servicio.nombre; // Nombre del servicio que se mostrará
                        servicioSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error cargando los servicios:', error));
        });

        // Comprobar el valor del servicio seleccionado al enviar el formulario
        document.getElementById('ratingForm').addEventListener('submit', (event) => {
            const servicioSelect = document.getElementById('servicio');
            if (servicioSelect.value === "" || servicioSelect.value === "0") {
                event.preventDefault(); // Detiene el envío del formulario
                alert('Por favor selecciona un servicio válido.');
            }
        });
        
    </script>
</body>
</html>
