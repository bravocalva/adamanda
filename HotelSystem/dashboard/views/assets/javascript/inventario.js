// inventario.js - Versión corregida
let habitaciones = [];

async function cargarHabitaciones() {
    try {
        const response = await fetch("http://localhost/adamanda/api_rest_hotel/habitaciones");
        habitaciones = await response.json();
        mostrarHabitaciones();
    } catch (error) {
        console.error("Error cargando habitaciones:", error);
    }
}

function mostrarHabitaciones(filtro = "") {
    const container = document.getElementById("habitacionesContainer");
    if (!container) return; // Validación adicional

    container.innerHTML = "";

    const filtradas = habitaciones.filter(hab =>
        hab.numero.toString().includes(filtro)
    );

    filtradas.forEach(hab => {
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
          <a href="inventario_habitacion.php?id=${hab.id_habitacion}" class="small-box-footer">
            Ver inventario <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    `;
    });

    if (filtradas.length === 0) {
        container.innerHTML = ` 
<div class="container-fluid">
  <div class="row justify-content-center"> <!-- Fila centrada -->
    <div class="col-lg-4 col-md-12 col-sm-12 text-center"> <!-- Columna centrada -->
      <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i> <!-- Icono 3x más grande -->
      <h5 class="text-muted"><strong>No se encontraron habitaciones</strong></h5>
    </div>
  </div>
</div>

    `;
    }
}

// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
    // Ahora sí podemos seleccionar el elemento
    const buscador = document.getElementById("buscador");

    if (buscador) { // Validamos que exista
        buscador.addEventListener("input", (e) => {
            mostrarHabitaciones(e.target.value);
        });

        cargarHabitaciones(); // Cargamos los datos
    } else {
        console.error("No se encontró el elemento con ID 'buscador'");
    }
});