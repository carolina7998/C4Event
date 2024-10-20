/*document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los botones de edición
    const editButtons = document.querySelectorAll('.edit-button');

    // Agregar un evento de clic a cada botón de edición
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Obtener los datos del servicio desde el botón
            const codigo = this.getAttribute('data-codigo');
            const nombre = this.getAttribute('data-nombre');
            const valor = this.getAttribute('data-valor');
            const categoria = this.getAttribute('data-categoria');
            const imagen = this.getAttribute('data-imagen');

            // Llenar el formulario de edición con los datos del servicio
            document.getElementById('edit_codigo_servicio').value = codigo;
            document.getElementById('edit_nombre_servicio').value = nombre;
            document.getElementById('edit_valor_servicio').value = valor;
            document.getElementById('edit_categoria_servicio').value = categoria;

            // Colocar la imagen del servicio en el formulario (si hay una)
            const imagenElement = document.getElementById('edit_imagen_servicio');
            if (imagen) {
                imagenElement.src = imagen;
                imagenElement.style.display = 'block';
            } else {
                // Ocultar la imagen si no hay una
                imagenElement.style.display = 'none';
            }

            // Mostrar la ventana modal de edición
            const modal = new bootstrap.Modal(document.getElementById('modalEditService'));
            modal.show();
        });
    });
});*/


$(document).ready(function () {
    $('.edit-button').click(function () {
        let codigo = $(this).data('codigo');
        let nombre = $(this).data('nombre');
        let valor = $(this).data('valor');
        let categoria = $(this).data('categoria');
        let imagen = $(this).data('imagen');

        $('#edit_codigo_servicio').val(codigo);
        $('#edit_nombre_servicio').val(nombre);
        $('#edit_valor_servicio').val(valor);
        $('#edit_categoria_servicio').val(categoria);

        // Muestra la imagen actual
        if (imagen) {
            $('#imagen-subida').html('<img src="' + imagen + '" alt="Imagen actual" width="100">');
        } else {
            $('#imagen-subida').html('');
        }

        $('#modalEditService').modal('show');
    });

    // Nuevo código para el botón de crear servicio
    $('#botoncrear').click(function () {
        Swal.fire({
            title: 'Crear Nuevo Servicio',
            text: "¿Estás seguro de que quieres crear un nuevo servicio?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, crear',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Abre el modal si el usuario confirma
                $('#modaladmin').modal('show');
            }
        });
    });
});






