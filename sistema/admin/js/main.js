const servicios = ['Servicio A', 'Servicio B', 'Servicio C', 'Servicio D', 'Servicio E']; // Reemplazar con los nombres de los servicios
const veces_solicitado = [30, 25, 20, 15, 10]; // Reemplazar con la cantidad de veces que cada servicio ha sido solicitado

const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: servicios,
        datasets: [{
            label: 'Veces Solicitado',
            data: veces_solicitado,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
