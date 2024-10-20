let servicesPerPage = 3;

        // Mostrar y ocultar detalles del servicio
        function toggleServiceDetails(serviceId) {
            var details = document.getElementById('details-' + serviceId);
            details.style.display = (details.style.display === 'none' || details.style.display === '') ? 'block' : 'none';
        }

        function selectService(serviceId, categoriaId) {
            var cantidad = document.getElementById('cantidad-' + serviceId).value;
            var observaciones = document.getElementById('observaciones-' + serviceId).value;
            var valorUnitario = parseFloat(document.getElementById('valor-' + serviceId).innerText);

            if (cantidad && !isNaN(valorUnitario)) {
                var serviceBox = document.getElementById('service-' + serviceId);
                var crudTable = document.getElementById('crud-table-body');
                var valorTotal = parseFloat(cantidad) * valorUnitario;

                // Verifica si el servicio ya fue agregado para evitar duplicados
                var existingRow = document.getElementById('crud-' + serviceId);
                if (existingRow) {
                    alert('Este servicio ya ha sido seleccionado.');
                    return;
                }

                // Crear nueva fila en la tabla
                var newRow = document.createElement('tr');
                newRow.id = 'crud-' + serviceId;
                newRow.innerHTML = `
                    <td>${serviceBox.querySelector('h5').innerText}</td>
                    <td>${cantidad}</td>
                    <td>${valorTotal.toFixed(2)}</td>
                    <td>${observaciones}</td>
                    <td> <button type="button" class="bin-button" onclick="deselectService(${serviceId}, ${categoriaId})"><svg class="bin-top" viewBox="0 0 39 7" fill="none"xmlns="http://www.w3.org/2000/svg" >
                            <line y1="5" x2="39" y2="5" stroke="white" stroke-width="4"></line> <line x1="12"  y1="1.5"  x2="26.0357"  y2="1.5"  stroke="white"  stroke-width="3"></line>
                        </svg>
                            <svg
                                class="bin-bottom"  viewBox="0 0 33 39"  fill="none" xmlns="http://www.w3.org/2000/svg" >
                                <mask id="path-1-inside-1_8_19" fill="white">  <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path> </mask>
                                <path d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z"
                                fill="white"  mask="url(#path-1-inside-1_8_19)" ></path>
                                <path d="M12 6L12 29" stroke="white" stroke-width="4"></path>
                                <path d="M21 6V29" stroke="white" stroke-width="4"></path>
                            </svg>
                        </button>
                    </td>`;
                crudTable.appendChild(newRow);  // Asegurarse de que el tbody esté correctamente referenciado

                // Mostrar el CRUD si está oculto y ajustar el tamaño de las columnas
                var crudBox = document.getElementById('crud-container');
                var serviceContainer = document.getElementById('service-container-wrapper');
                crudBox.style.display = 'block';  // Mostramos el CRUD
                serviceContainer.classList.replace('col-md-12', 'col-md-7');  // Cambiamos el tamaño de la columna de servicios

                // Ocultar el servicio seleccionado de la lista original
                serviceBox.style.display = 'none';

                // Actualizar el total
                updateTotal();
            } else {
                alert("Por favor, complete la cantidad antes de seleccionar el servicio.");
            }
        }

        function deselectService(serviceId, categoriaId) {
            var serviceBox = document.getElementById('service-' + serviceId);
            var crudRow = document.getElementById('crud-' + serviceId);
            var categoryContainer = document.getElementById('category-' + categoriaId);
            var servicePosition = parseInt(serviceBox.getAttribute('data-posicion'));

            crudRow.parentNode.removeChild(crudRow);

            var siblings = categoryContainer.getElementsByClassName('service-box');
            if (servicePosition >= siblings.length) {
                categoryContainer.appendChild(serviceBox);
            } else {
                categoryContainer.insertBefore(serviceBox, siblings[servicePosition]);
            }
            serviceBox.style.display = 'block';

            document.getElementById('cantidad-' + serviceId).value = '';
            document.getElementById('observaciones-' + serviceId).value = '';

            document.getElementById('details-' + serviceId).style.display = 'none';

            updateTotal();
        }

        function updateTotal() {
            var crudTable = document.getElementById('crud-table-body');
            var rows = crudTable.getElementsByTagName('tr');
            var total = 0;
            for (var i = 0; i < rows.length; i++) {
                var valorTotal = parseFloat(rows[i].cells[2].innerText);
                total += valorTotal;
            }
            document.getElementById('total-suma').innerText = total.toFixed(2);
        }

        function deselectService(serviceId, categoriaId) {
            var serviceBox = document.getElementById('service-' + serviceId);
            var crudRow = document.getElementById('crud-' + serviceId);
            var categoryContainer = document.getElementById('category-' + categoriaId);
            var servicePosition = parseInt(serviceBox.getAttribute('data-posicion'));

            crudRow.parentNode.removeChild(crudRow);

            var siblings = categoryContainer.getElementsByClassName('service-box');
            if (servicePosition >= siblings.length) {
                categoryContainer.appendChild(serviceBox);
            } else {
                categoryContainer.insertBefore(serviceBox, siblings[servicePosition]);
            }
            serviceBox.style.display = 'block';

            document.getElementById('cantidad-' + serviceId).value = '';
            document.getElementById('observaciones-' + serviceId).value = '';

            document.getElementById('details-' + serviceId).style.display = 'none';

            updateTotal();
        }

        function updateTotal() {
            var crudTable = document.getElementById('crud-table-body');
            var rows = crudTable.getElementsByTagName('tr');
            var total = 0;
            for (var i = 0; i < rows.length; i++) {
                var valorTotal = parseFloat(rows[i].cells[2].innerText);
                total += valorTotal;
            }
            document.getElementById('total-suma').innerText = total.toFixed(2);
        }

       

        // Mostrar los primeros 3 servicios
        function showInitialServices(categoriaId) {
            const services = document.querySelectorAll(`#category-${categoriaId} .service-box`);
            services.forEach((service, index) => {
                service.style.display = (index < servicesPerPage) ? 'inline-block' : 'none';
            });
        }

        function moveLeft(categoriaId) {
            const services = document.querySelectorAll(`#category-${categoriaId} .service-box`);
            let firstVisibleIndex = -1;

            services.forEach((service, index) => {
                if (service.style.display !== 'none' && firstVisibleIndex === -1) {
                    firstVisibleIndex = index;
                }
            });

            if (firstVisibleIndex > 0) {
                for (let i = firstVisibleIndex; i < firstVisibleIndex + servicesPerPage; i++) {
                    if (services[i]) services[i].style.display = 'none';
                }

                for (let i = firstVisibleIndex - servicesPerPage; i < firstVisibleIndex; i++) {
                    if (services[i]) services[i].style.display = 'inline-block';
                }
            }
        }

        function moveRight(categoriaId) {
            const services = document.querySelectorAll(`#category-${categoriaId} .service-box`);
            let lastVisibleIndex = -1;

            services.forEach((service, index) => {
                if (service.style.display !== 'none') {
                    lastVisibleIndex = index;
                }
            });

            if (lastVisibleIndex < services.length - 1) {
                for (let i = lastVisibleIndex - servicesPerPage + 1; i <= lastVisibleIndex; i++) {
                    if (services[i]) services[i].style.display = 'none';
                }

                for (let i = lastVisibleIndex + 1; i <= lastVisibleIndex + servicesPerPage; i++) {
                    if (services[i]) services[i].style.display = 'inline-block';
                }
            }
        }


        //MOSTRAR EL CALENDARIO
        $(document).ready(function() {
            $.ajax({
                url: 'obtener_fechas_reservadas.php',
                type: 'GET',
                dataType: 'json',
                success: function(fechasReservadas) {
                    //console.log('Fechas reservadas:', fechasReservadas); // Solo para depuración

                    var calendarEl = document.getElementById('calendario');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        selectable: true,
                        dateClick: function(info) {
                            var fecha = info.dateStr;
                            if (fechasReservadas.includes(fecha)) {
                                alert('Esta fecha ya está reservada. Por favor, selecciona otra.');
                            } else {
                                $('#fecha_evento').val(fecha);
                                alert('Fecha seleccionada: ' + fecha);
                            }
                        },
                        events: fechasReservadas.map(fecha => ({
                            start: fecha,
                            display: 'background',
                            color: '#ff0000',
                        })),
                        validRange: {
                            start: new Date().toISOString().slice(0, 10),
                        }
                    });
                    calendar.render();
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener fechas reservadas:', error);
                }
            });
        });


        //desplegar el calendario pulsando en el input

        $(document).ready(function() {
        // Al hacer clic en el input, mostramos el calendario y lo posicionamos
        $('#fecha_evento').on('click', function() {
            // Obtener la posición del input
            var inputOffset = $(this).offset();
            var inputHeight = $(this).outerHeight();

            // Posicionar el contenedor del calendario justo debajo del input
            $('#calendario_container').css({
                top: inputOffset.top + inputHeight + 'px', // Debajo del input
                left: inputOffset.left + 'px', // Alineado al input
                display: 'block' // Mostrar el calendario
            });
        });

        // Detectar clic fuera del calendario para cerrarlo
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#calendario_container, #fecha_evento').length) {
                $('#calendario_container').hide();
            }
        });

    // Cargar las fechas reservadas
    $.ajax({
        url: 'obtener_fechas_reservadas.php',
        type: 'GET',
        dataType: 'json',
        success: function(fechasReservadas) {
            var calendarEl = document.getElementById('calendario');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                dateClick: function(info) {
                    var fecha = info.dateStr;
                    if (fechasReservadas.includes(fecha)) {
                        alert('Esta fecha ya está reservada. Por favor, selecciona otra.');
                    } else {
                        $('#fecha_evento').val(fecha);  // Insertar la fecha seleccionada en el input
                        $('#calendario_container').hide();  // Cerrar el calendario
                    }
                },
                events: fechasReservadas.map(fecha => ({
                    start: fecha,
                    display: 'background',
                    color: '#ff0000',
                })),
                validRange: {
                    start: new Date().toISOString().slice(0, 10),
                }
            });
            calendar.render();
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener fechas reservadas:', error);
        }
    });
});

    //codigo para mejorar nombres en el calendario y el idioma español

    var calendario = new FullCalendar.Calendar(calendarioEl, {
        initialView: 'dayGridMonth',
        locale: 'es', // Configurar el idioma a español
        selectable: true,
        dateClick: function(info) {
            var fecha = info.dateStr;
            if (fechasReservadas.includes(fecha)) {
                alert('Esta fecha ya está reservada. Por favor, selecciona otra.');
            } else {
                $('#fecha_evento').val(fecha);
                $('#calendario_container').hide();
            }
        },
        events: fechasReservadas.map(fecha => ({
            start: fecha,
            display: 'background',
            color: '#ff0000',
        })),
        validRange: {
            start: new Date().toISOString().slice(0, 10),
        }
    });
    calendar.render();