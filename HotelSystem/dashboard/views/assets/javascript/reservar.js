
// ==================== Funciones Utilitarias ====================
function obtenerFechaHoy() {
    const hoy = new Date();
    return hoy.toLocaleDateString('en-CA').split('T')[0];
}



function sumarDias(fechaStr, dias) {
    const fecha = new Date(fechaStr);
    fecha.setDate(fecha.getDate() + dias);
    return fecha.toLocaleDateString('en-CA').split('T')[0];
}

// ==================== Cargar tipos de habitación ====================
async function cargarTiposDeHabitacion() {
    const select = document.getElementById("filtro_tipo_habitacion");
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

// ==================== Fechas ====================
function actualizarFechaSalidaMinima() {
    const fechaEntrada = document.getElementById('fecha_entrada');
    const fechaSalida = document.getElementById('fecha_salida');

    if (fechaEntrada.value) {
        fechaSalida.min = sumarDias(fechaEntrada.value, 1);
        fechaSalida.disabled = false;
    }
}

function inicializarFechas() {
    const fechaEntrada = document.getElementById('fecha_entrada');
    const fechaHoy = obtenerFechaHoy();

    fechaEntrada.min = fechaHoy;
    fechaEntrada.addEventListener('input', actualizarFechaSalidaMinima);
    actualizarFechaSalidaMinima(); // Inicial
}

// ==================== Control de tipo de reserva ====================
function controlarTipoDeReserva() {
    const fechaEntrada = document.getElementById('fecha_entrada');
    const fechaHoy = obtenerFechaHoy();

    document.querySelectorAll('input[name="tipo_reserva"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === "inmediata") {
                fechaEntrada.value = fechaHoy;
                fechaEntrada.disabled = true;
                actualizarFechaSalidaMinima(); // Actualizamos fecha mínima de salida
            } else {
                fechaEntrada.disabled = false;
            }
            document.getElementById("btnDisponible").disabled = false;
        });
    });
}

// ==================== Generar Tarjetas de Habitaciones ====================
function crearCardHabitacion(habitacion, tipoReserva, entrada, salida) {
    const botonTexto = tipoReserva === 'inmediata' ? 'Hospedar ahora' : 'Reservar';
    const botonColor = tipoReserva === 'inmediata' ? 'btn-success' : 'btn-primary';
    const botonLink = tipoReserva === 'inmediata'
        ? `hospedar.php?room_id=${habitacion.id_habitacion}&fecha_e=${entrada}&fecha_s=${salida}`
        : `procesar_reserva.php?room_id=${habitacion.id_habitacion}&fecha_e=${entrada}&fecha_s=${salida}`;

    return `
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card mb-3 h-100">
                <img src="${habitacion.ruta_imagen}" class="card-img-top" alt="Imagen habitación ${habitacion.numero}" style="height: 180px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-1">#${habitacion.numero} - ${habitacion.tipo_habitacion}</h5>
                    <p class="card-text small" style="flex-grow: 1;">${habitacion.descripcion.substring(0, 100)}${habitacion.descripcion.length > 100 ? "..." : ""}</p>
                    <p class="mb-1"><strong>Precio por noche:</strong> $${habitacion.precio}</p>
                    <a href="${botonLink}" class="btn ${botonColor} btn-block">${botonTexto}</a>
                </div>
            </div>
        </div>`;
}

// ==================== Buscar Habitaciones Disponibles ====================
function configurarFormularioBusqueda() {
    document.getElementById("form-buscar").addEventListener("submit", function (e) {
        e.preventDefault();

        const entrada = document.getElementById("fecha_entrada").value;
        const salida = document.getElementById("fecha_salida").value;
        const tipoHabitacionFiltro = parseInt(document.getElementById("filtro_tipo_habitacion").value);
        const tipoReserva = document.querySelector('input[name="tipo_reserva"]:checked')?.value;

        const container = document.getElementById("room-cards-container");
        const loader = document.getElementById("loader");

        container.innerHTML = "";
        loader.style.display = "block";

        fetch('http://localhost/adamanda/api_rest_hotel/habitaciones_fecha', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ fecha_e: entrada, fecha_s: salida })
        })
        .then(response => response.json())
        .then(data => {
            loader.style.display = "none";

            if (tipoHabitacionFiltro) {
                data = data.filter(h => h.id_tipo_hab === tipoHabitacionFiltro);
            }

            if (data.length === 0) {
                container.innerHTML = "<div class='col-12 text-center'><p>No hay habitaciones disponibles en esas fechas.</p></div>";
                return;
            }

            data.forEach(habitacion => {
                container.innerHTML += crearCardHabitacion(habitacion, tipoReserva, entrada, salida);
            });
        })
        .catch(error => {
            console.error("Error al obtener habitaciones:", error);
            loader.style.display = "none";
            container.innerHTML = "<div class='col-12 text-center text-danger'><p>Ocurrió un error al consultar.</p></div>";
        });
    });
}
document.addEventListener("DOMContentLoaded", function () {
    inicializarFechas();
    controlarTipoDeReserva();
    configurarFormularioBusqueda();
    cargarTiposDeHabitacion();
});

