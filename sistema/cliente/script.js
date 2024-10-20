
    function deselectService(serviceId, categoriaId) {
        var serviceBox = document.getElementById('service-' + serviceId);
        var crudRow = document.getElementById('crud-' + serviceId);
        var categoryContainer = document.getElementById('category-' + categoriaId);

        crudRow.parentNode.removeChild(crudRow);
        
        // Reinsertar el servicio en su posición original
        serviceBox.style.display = 'block';
        categoryContainer.appendChild(serviceBox);

        // Limpiar la cantidad y las observaciones
        document.getElementById('cantidad-' + serviceId).value = '';
        document.getElementById('observaciones-' + serviceId).value = '';

        // Ocultar los detalles del servicio
        document.getElementById('details-' + serviceId).style.display = 'none';

        updateTotal();
    }

