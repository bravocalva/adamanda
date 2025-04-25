let habitaciones = [];
const hoy = new Date(); //fecha y hora actual
const yyyy = hoy.getFullYear();
const mm = String(hoy.getMonth() + 1).padStart(2, "0");
const dd = String(hoy.getDate()).padStart(2, "0");
const fecha_hoy = `${yyyy}-${mm}-${dd}`;

async function cargarHabitaciones() {
    try {
        const response = await fetch("http://localhost/adamanda/api_rest_hotel/all_hab_reservado");
        habitaciones = await response.json();

    } catch (error) {
        console.error("Error cargando habitaciones:", error);
    }

    const container = document.getElementById("habitacionesContainer");
    if (!container)
        return;

    container.innerHTML = "";
    console.log(habitaciones);
    habitaciones.forEach(hab => {
        container.innerHTML += `
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="small-box bg-warning">
                <div class="icon" style="opacity: 0.7; font-size: 70px;">
                    <i class="fas fa-hotel"></i>
                </div>
                <div class="inner">
                    <h4>Habitación</h4>
                    <h3> No. ${hab.habitaciones}</h3>
                    <h5>Fecha entrada: ${hab.fecha_entrada}</h5>
                    <span>ID Reservacion: ${hab.id_reservacion}</span><br>
                    <span>Cliente: ${hab.cliente}</span>
                </div>
                    <div class="small-box-footer" style="cursor: pointer;" onclick="cambiarStatus(${hab.id_reservacion},${hab.id_habitacion},'${hab.fecha_entrada}')">
      Empezar Hospedaje <i class="fas fa-arrow-circle-right"></i>
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

function cambiarStatus($id_reservacion, $id_habitacion, $fecha_entrada) {

    console.log(fecha_hoy);
    console.log("reservacionId: ", $id_reservacion);
    console.log("habitacionId: ", $id_habitacion);
    console.log($fecha_entrada);

    if (fecha_hoy === $fecha_entrada) {

        Swal.fire({
            text: "¿Iniciar la reservacion de esta habitación?",
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
                console.log("Cambiando status de la reservacion con ID: ", $id_reservacion);


                fetch(`http://localhost/adamanda/api_rest_hotel/check_in_reservacion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_reservacion: $id_reservacion,
                        id_habitacion: $id_habitacion,
                    })
                })
                    .then(() => {
                        cargarHabitaciones();
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                    });

            }
        });
    }
    else if (fecha_hoy < $fecha_entrada) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha de entrada',
            text: 'La fecha de entrada aun no es el dia actual.',
            confirmButtonText: 'Aceptar'
        });

    }
    else if (fecha_hoy > $fecha_entrada) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha de entrada',
            text: 'La fecha de entrada ya pasó. ¿Desea continuar?',
            confirmButtonText: 'Aceptar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("Cambiando status de la reservacion con ID: ", $id_reservacion);
                fetch(`http://localhost/adamanda/api_rest_hotel/check_in_reservacion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_reservacion: $id_reservacion,
                        id_habitacion: $id_habitacion,
                    })
                })
                    .then(() => {
                        cargarHabitaciones();
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                    });

            }
        })
            ;

    }


}

document.addEventListener("DOMContentLoaded", () => {
    cargarHabitaciones();
});