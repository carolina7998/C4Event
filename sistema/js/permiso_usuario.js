function toggleUserStatus(docid, currentStatus) {
    const button = document.getElementById('toggleButton' + docid);
    const newStatus = currentStatus === 'activo' ? 'inactivo' : 'activo';

    // Cambiar el texto del botón inmediatamente para reflejar el nuevo estado
    button.innerText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

    // Realizar la solicitud AJAX al servidor
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'procesar_estado_usuarios.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log('Respuesta del servidor: ' + xhr.responseText);
            } else {
                console.error('Error en la solicitud AJAX: ' + xhr.status);
            }
        }
    };
    xhr.send('usuario_id=' + docid + '&estado=' + newStatus);
}
