const params = new URLSearchParams(window.location.search);
const fechaEntrada = params.get('fecha_e');
const fechaSalida = params.get('fecha_s');
const tipoHabitacion = params.get('tipo_hab');
const contenedor = document.getElementById('habitaciones-container');
const select = document.getElementById("tipo_habitacion");
const hoy = new Date();
// Formatear la fecha de hoy
const yyyy = hoy.getFullYear();
const mm = String(hoy.getMonth() + 1).padStart(2, "0");
const dd = String(hoy.getDate()).padStart(2, "0");
const formatDate = `${yyyy}-${mm}-${dd}`;
console.log("Fecha de hoy en formato: ", formatDate);

document.getElementById('fechaEntrada').setAttribute("min", formatDate); //fecha de entrada minima (fecha actual)
//seteamos los input con las fechas recibidas
document.getElementById('fechaEntrada').value = fechaEntrada;
document.getElementById('fechaSalida').value = fechaSalida;

async function cargarTiposDeHabitacion() {
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/tipohabitacion');
        const tipos = await response.json();
        tipos.forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo.id_tipo_hab;
            option.textContent = tipo.nombre;
            select.appendChild(option);
        });
    } catch (error) {
        console.error("Error cargando tipos de habitación:", error);
    }
}

function cargarHabitaciones() {
    let filtradas = [];
    fetch('http://localhost/adamanda/api_rest_hotel/habitaciones_fecha', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            fecha_e: fechaEntrada,
            fecha_s: fechaSalida
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log("Datos de habitaciones:", data);
            contenedor.innerHTML = '';
            if (tipoHabitacion != 0) {
                filtradas = data.filter(hab => hab.id_tipo_hab == tipoHabitacion);
            }
            else {
                filtradas = data;
            }
            filtradas.forEach(hab => {
                const card = document.createElement('div');
                card.className = 'col-12 col-sm-6 col-md-4 col-lg-3';
                card.innerHTML = `
                <div class="room__card room__card--small">
                    <div class="room__card__image">
                        <img class="room_img" src="${hab.ruta_imagen}" />
                    </div>
                    <div class="room__card__details">
                    <ul>Habitacion No. <strong>${hab.numero}</strong></ul>
                        <h4>${hab.tipo_habitacion}</h4>
                        <p>${hab.descripcion}</p>
                        <h5>Desde <span>$${parseFloat(hab.precio).toFixed(2)}</span></h5>
                        <button class="btn btn-primary">Reservar Ahora</button>
                    </div>
                </div>
            `;
                contenedor.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error al cargar habitaciones:", error);
            contenedor.innerHTML = `<div class="col-12"><p class="text-danger">Error al cargar habitaciones disponibles.</p></div>`;
        });
}

function actualizarHabitaciones() {
    const entrada = document.getElementById("fechaEntrada").value;
    const salida = document.getElementById("fechaSalida").value;
    const tipo_hab = document.getElementById("tipo_habitacion").value;
    console.log("Fecha entrada seleccionada: ", entrada);
    console.log("Fecha salida seleccionada: ", salida);
    console.log("Tipo Habitacion seleccionada: ", tipo_hab);

    let filtradas = [];
    fetch('http://localhost/adamanda/api_rest_hotel/habitaciones_fecha', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            fecha_e: entrada,
            fecha_s: salida
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log("Datos de habitaciones:", data);
            contenedor.innerHTML = '';
            if (tipo_hab != 0) {
                filtradas = data.filter(hab => hab.id_tipo_hab == tipo_hab);
            }
            else {
                filtradas = data;
            }
            filtradas.forEach(hab => {
                const card = document.createElement('div');
                card.className = 'col-12 col-sm-6 col-md-4 col-lg-3';
                card.innerHTML = `
                <div class="room__card room__card--small">
                    <div class="room__card__image">
                        <img class="room_img" src="${hab.ruta_imagen}" />
                    </div>
                    <div class="room__card__details">
                    <ul>Habitacion No. <strong>${hab.numero}</strong></ul>
                        <h4>${hab.tipo_habitacion}</h4>
                        <p>${hab.descripcion}</p>
                        <h5>Desde <span>$${parseFloat(hab.precio).toFixed(2)}</span></h5>
                        <button class="btn btn-primary">Reservar Ahora</button>
                    </div>
                </div>
            `;
                contenedor.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error al cargar habitaciones:", error);
            contenedor.innerHTML = `<div class="col-12"><p class="text-danger">Error al cargar habitaciones disponibles.</p></div>`;
        });




}

cargarTiposDeHabitacion();
cargarHabitaciones();
