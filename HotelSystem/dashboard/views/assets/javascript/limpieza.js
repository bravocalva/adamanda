let habitaciones = [];

async function cargarHabitaciones() {
    try {
        const response = await fetch("http://localhost/adamanda/api_rest_hotel/all_hab_limpieza");
        habitaciones = await response.json();
        mostrarHabitaciones();
    } catch (error) {
        console.error("Error cargando habitaciones:", error);
    }
}

function mostrarHabitaciones() {
    const container = document.getElementById("habitacionesContainer");
    if (!container) return;

    container.innerHTML = "";

    habitaciones.forEach(hab => {
        container.innerHTML += `
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="small-box bg-primary">
                <div class="icon" style="opacity: 0.7; font-size: 70px;">
                    <i class="fas fa-hotel"></i>
                </div>
                <div class="inner">
                    <h4>Habitación</h4>
                    <h3> No. ${hab.numero}</h3>
                </div>
                    <div class="small-box-footer" style="cursor: pointer;" onclick="terminarLimpieza(${hab.id_habitacion})">
      Terminar Limpieza <i class="fas fa-arrow-circle-right"></i>
    </div>

            </div>
        </div>
        `;
    });

    if (habitaciones.length === 0) {
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

function terminarLimpieza(id) {
    Swal.fire({
        text: "¿La habitación ya está limpia?",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
        showCloseButton: false,
        icon: null,
        customClass: {
            popup: 'swal2-no-icon'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Terminando Limpieza en Hab: ", id);
            fetch(`http://localhost/adamanda/api_rest_hotel/cambiar_disponible/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                cargarHabitaciones();
            });


        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    cargarHabitaciones();
});