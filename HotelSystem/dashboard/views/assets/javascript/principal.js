async function cargarHabitacionMasReservada() {
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/habitacion_mas_reservada');
        const habitacion = await response.json();
        console.log(habitacion);
        document.getElementById("noHab").textContent = habitacion.numero_habitacion;
        document.getElementById("tipoHab").textContent = habitacion.tipo_habitacion;
        document.getElementById("precioHab").textContent = habitacion.precio;
        document.getElementById("imagenHab").src = habitacion.ruta_imagen;
        document.getElementById("descripcionHab").textContent = habitacion.descripcion;
        document.getElementById("estadoHab").textContent = habitacion.estado;
    document.getElementById("habMasReservada").textContent = habitacion.numero_habitacion;


    } catch (error) {
        console.error('Error:', error);
    }
}

function obtenerNombreMes(numeroMes) {
    const nombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    return nombres[numeroMes - 1];
  }

async function cargarGraficoReservas() {
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/reservasAnual'); // <-- ajusta esta ruta
        const datos = await response.json();

        // Crear arrays para labels (meses) y valores
        const labels = datos.map(item => obtenerNombreMes(item.mes));
        const valores = datos.map(item => item.total_reservas);

        // Crear o actualizar el gráfico
        const ctx = document.getElementById('myChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Reservas por mes',
                    data: valores,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    } catch (error) {
        console.error('Error al cargar datos de la API:', error);
    }
}


document.addEventListener("DOMContentLoaded", () => {
    cargarHabitacionMasReservada();
    cargarGraficoReservas();
});

