$(document).ready(function () {
    $('.edit-button').click(function () {
        let docid = $(this).data('docid'); // Obtener el docid del usuario
        let rol = $(this).data('rol'); // Obtener el rol actual del usuario

        // Asignar los valores a los campos del modal
        $('#edit_docid').val(docid);
        $('#edit_rol').val(rol);

        // Mostrar el modal de edición
        $('#modalEditUser').modal('show');
    });
});
