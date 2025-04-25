let habitaciones = [];

async function cargarHabitaciones() {
    try {
        const response = await fetch("http://localhost/adamanda/api_rest_hotel/habitaciones_ocupadas");
        habitaciones = await response.json();
        mostrarHabitaciones();
    } catch (error) {
        console.error("Error cargando habitaciones:", error);
    }
}

function mostrarHabitaciones(filtroTexto = "") {
    const container = document.getElementById("habitacionesContainer");
    if (!container) return;

    container.innerHTML = "";

    const filtroFecha = document.getElementById("filtroFecha")?.value || "todos";

    const hoy = new Date().toLocaleDateString('en-CA');
    const textoHoy = `Fecha Salida. ${hoy}`;

    const filtradas = habitaciones.filter(hab => {
        const coincideTexto = hab.numero_Habitacion.toString().includes(filtroTexto);
        const textoSalida = `Fecha Salida. ${hab.fecha_salida}`;

        if (filtroFecha === "hoy") {
            return coincideTexto && textoSalida === textoHoy;
        }

        return coincideTexto; // todos
    });

    filtradas.forEach(hab => {
        container.innerHTML += `
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="small-box bg-danger">
                <div class="icon" style="opacity: 0.7; font-size: 70px;">
                    <i class="fas fa-hotel"></i>
                </div>
                <div class="inner">
                    <h4>Habitación</h4>
                    <h3> No. ${hab.numero_Habitacion}</h3>
                    <strong><h6> Fecha Salida. ${hab.fecha_salida}</h6></strong>
                </div>
                <a href="check_out.php?habitacionId=${hab.id_habitacion}&reservaId=${hab.id_reservacion}" class="small-box-footer">
                    Realizar Check-Out <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        `;
    });

    if (filtradas.length === 0) {
        container.innerHTML = `
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><strong>No se encontraron habitaciones</strong></h5>
                </div>
            </div>
        </div>
        `;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const buscador = document.getElementById("buscador");
    const filtroFecha = document.getElementById("filtroFecha");

    if (buscador) {
        buscador.addEventListener("input", (e) => {
            mostrarHabitaciones(e.target.value);
        });
    }

    if (filtroFecha) {
        filtroFecha.addEventListener("change", () => {
            const filtroTexto = buscador?.value || "";
            mostrarHabitaciones(filtroTexto);
        });
    }

    cargarHabitaciones();
});
