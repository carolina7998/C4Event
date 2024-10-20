async function fetchData(url) {
    const response = await fetch(url);
    return response.json();
}

function createChart(ctx, type, data, options) {
    return new Chart(ctx, {
        type: type,
        data: data,
        options: options
    });
}

async function renderCharts() {
    try {
        // Obtener datos para el gráfico de servicios
        const serviciosData = await fetchData('get_servicios.php');
        const labelsServicios = serviciosData.map(item => item.nombre);
        const cantidadesServicios = serviciosData.map(item => item.cantidad_solicitudes);

        // Configuración del gráfico de servicios
        const ctxServicios = document.getElementById('serviciosChart').getContext('2d');
        createChart(ctxServicios, 'bar', {
            labels: labelsServicios,
            datasets: [{
                label: 'Cantidad de Solicitudes',
                data: cantidadesServicios,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        }, {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#303030' },
                    grid: { color: '#ddd' }
                },
                x: {
                    ticks: { color: '#303030' },
                    grid: { color: '#ddd' }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#666',
                    borderWidth: 1
                }
            }
        });

        // Obtener datos para el gráfico de tipos de eventos
// Función para obtener los datos
async function fetchData(url) {
    const response = await fetch(url);
    const data = await response.json();
    return data;
}

// Crear gráfico
function createChart(ctx, type, data, options) {
    return new Chart(ctx, {
        type: type,
        data: data,
        options: options
    });
}





async function init() { 
    // Obtener datos para el gráfico de tipos de eventos
    const eventosTiposData = await fetchData('get_eventos_tipos.php');
    const labelsTipos = eventosTiposData.map(item => item.tipo_evento);
    const cantidadesTipos = eventosTiposData.map(item => item.cantidad_eventos);

    // Configuración del gráfico de tipos de eventos
    const ctxTipos = document.getElementById('eventosTiposChart').getContext('2d');
    createChart(ctxTipos, 'doughnut', { // Usar 'doughnut' para un gráfico de dona
        labels: labelsTipos,
        datasets: [{
            label: 'Tipos de Eventos',
            data: cantidadesTipos,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                '#FF9F40', '#9966FF', '#C9CBCF', '#FFD700',
                '#B22222', '#32CD32'
            ], // Colores únicos
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    }, {
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    color: '#5d5d5f', // Cambiar color de etiquetas a negro
                    font: {
                        size: 14 // Ajustar el tamaño de las etiquetas si es necesario
                    }
                }
            },
            tooltip: {
                backgroundColor: '#f0f0f0',
                titleColor: '#303030',
                bodyColor: '#303030',
                borderColor: '#666',
                borderWidth: 1
            }
        },
        layout: {
            padding: 20
        },
        responsive: true,
        maintainAspectRatio: true, // Mantener la relación de aspecto para evitar que crezca hacia abajo
        elements: {
            arc: {
                borderWidth: 2
            }
        }
    });
}







init();


        // Obtener datos para el gráfico de eventos por mes
        const eventosMesData = await fetchData('get_eventos_mes.php');
        const labelsMes = Object.keys(eventosMesData); // Nombres de los meses
        const cantidadesMes = Object.values(eventosMesData); // Cantidades de eventos por mes

        // Configuración del gráfico de eventos por mes
        const ctxMes = document.getElementById('eventosMesChart').getContext('2d');
        createChart(ctxMes, 'line', {
            labels: labelsMes,
            datasets: [{
                label: 'Eventos por Mes',
                data: cantidadesMes,
                borderColor: '#C85C8E',
                backgroundColor: 'rgba(200, 92, 142, 0.3)',
                borderWidth: 2,
                pointBackgroundColor: '#C85C8E',
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4 // Curvatura de la línea para un efecto suave
            }]
        }, {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#303030' },
                    grid: { color: '#ddd' }
                },
                x: {
                    ticks: { color: '#303030' },
                    grid: { color: '#ddd' }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#666',
                    borderWidth: 1
                }
            }
        });

    } catch (error) {
        console.error('Error fetching or rendering data:', error);
    }
}

// Ejecutar la función para renderizar los gráficos al cargar la página
window.onload = renderCharts;