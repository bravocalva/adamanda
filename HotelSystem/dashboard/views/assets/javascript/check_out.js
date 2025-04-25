const urlParams = new URLSearchParams(window.location.search);
const habitacionId = urlParams.get('habitacionId');
const reservaId = urlParams.get('reservaId');

async function obtenerInfoHab() {
  const response = await fetch(`http://localhost/adamanda/api_rest_hotel/habitaciones_info/${habitacionId}`);
  const habitacion = await response.json();
  return habitacion;
}

async function obtenerInfoReservacion() {
  const response = await fetch(`http://localhost/adamanda/api_rest_hotel/reservas/${reservaId}`);
  const reserva = await response.json();
  console.log(reserva);
  return reserva;

}

async function cargarDatos() {
  const habitacion = await obtenerInfoHab();
  const reserva = await obtenerInfoReservacion();
  $('#info-habitacion-id').text(habitacion.id_habitacion);
  $('#info-habitacion-numero').text(habitacion.numero);
  $('#header-number').text(habitacion.numero);
  $('#info-habitacion-tipo').text(habitacion.tipo_habitacion);
  $('#info-habitacion-imagen').attr('src', habitacion.ruta_imagen);
  $('#info-habitacion-descripcion').text(habitacion.descripcion);
  $('#id_precio').text(habitacion.precio);
  // ------------------------------------------------------
  $('#idReservacion').text(reservaId);
  $('#fechaEntrada').text(reserva.fecha_entrada);
  $('#fechaSalida').text(reserva.fecha_salida);
  $('#cantidadNoches').text(reserva.noches);
  $('#adelanto').text(reserva.adelanto);
  $('#total').text(reserva.total_restante);

  actualizarTotalFinal();
}

function cambiarFaltante(id, cambio, max) {
  const input = document.getElementById(`faltante-${id}`);
  const btnMinus = document.getElementById(`btn-minus-${id}`);
  const btnPlus = document.getElementById(`btn-plus-${id}`);

  let valor = Math.min(Math.max((+input.value || 0) + cambio, 0), max);
  input.value = valor;

  btnMinus.disabled = valor <= 0;
  btnPlus.disabled = valor >= max;
  actualizarTotalFaltante();

}

function actualizarTotalFaltante() {
  let total = 0;

  const filas = document.querySelectorAll("#tablaArticulos tbody tr");
  filas.forEach((fila) => {
    const id = fila.children[0].textContent;
    const precio = parseFloat(fila.children[3].textContent);
    const input = document.getElementById(`faltante-${id}`);
    const cantidad = parseInt(input.value) || 0;
    total += precio * cantidad;
  });

  document.getElementById("total-faltante").textContent = `$${total.toFixed(2)}`;
}

function actualizarTotalCargos() {
  let total = 0;

  const filas = document.querySelectorAll("#tablaCargos tbody tr");
  filas.forEach((fila) => {
    const id = fila.children[0].textContent;
    const precio = parseFloat(fila.children[2].textContent);
    const input = document.getElementById(`faltante-${id}`);
    const cantidad = parseInt(input.value) || 0;
    total += precio * cantidad;
  });
}

function actualizarTotalFinal() {
  const restante_pagar = parseFloat(document.getElementById("total").textContent);
  const total_inventario = parseFloat(document.getElementById("total-faltante").textContent.replace('$', ''));
  const total_cargos = parseFloat(document.getElementById("total-adicional").textContent.replace('$', ''));
  const total_final = restante_pagar + total_inventario + total_cargos;
  document.getElementById("totalFinalFactura").textContent = total_final;

}

async function obtenerInventario() {
  try {
    const response = await fetch(`http://localhost/adamanda/api_rest_hotel/inventario_hab/${habitacionId}`);
    const articulos = await response.json();

    if (articulos.error) {
      console.log("Error al obtener el inventario");
    }
    else {
      const tbody = document.querySelector("#tablaArticulos tbody");
      tbody.innerHTML = ""; // Limpiar la tabla

      articulos.forEach(({ id_articulo, nombre_articulo, descripcion_articulo, precio, stock_actual }) => {
        tbody.innerHTML += `
          <tr>
            <td>${id_articulo}</td>
            <td>${nombre_articulo}</td>
            <td>${descripcion_articulo}</td>
            <td>${precio}</td>
            <td>${stock_actual}</td>
<td>
  <div class="d-flex justify-content-center align-items-center gap-1">
    <button class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center"
            onclick="cambiarFaltante(${id_articulo}, -1, ${stock_actual});actualizarTotalFinal()"
            id="btn-minus-${id_articulo}" style="height: 32px; width: 32px;">−</button>

    <input type="number" min="0" max="${stock_actual}" value="0"
           class="form-control text-center"
           id="faltante-${id_articulo}" readonly style="width: 60px; height: 32px;" />

    <button class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center"
            onclick="cambiarFaltante(${id_articulo}, 1, ${stock_actual}); actualizarTotalFinal()"
            id="btn-plus-${id_articulo}" style="height: 32px; width: 32px;">+</button>
  </div>
</td>

          </tr>
        `;
      });

    }


  } catch (error) {
    console.error("Error al obtener los datos:", error);
  }
}

async function actualizarCargosAdicionales() {
  try {
    const response = await fetch(`http://localhost/adamanda/api_rest_hotel/cargos/${reservaId}`);
    const cargos = await response.json();

    const tbody = document.querySelector("#tablaCargos tbody");
    tbody.innerHTML = "";

    let total = 0;

    cargos.forEach(cargo => {
      total += +cargo.monto; // va sumando cada monto

      tbody.innerHTML += `
        <tr>
          <td>${cargo.id_cargo}</td>
          <td>${cargo.descripcion}</td>
          <td>$${parseFloat(cargo.monto).toFixed(2)}</td>
          <td>
            <button class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
              onclick="eliminarCargo(${cargo.id_cargo})">Eliminar</button>
          </td>
        </tr>
      `;
    });

    // Total al final
    tbody.innerHTML += `
      <tr class="fw-bold bg-light">
        <td colspan="3" class="text-end">Total:</td>
        <td id="total-adicional">$${total.toFixed(2)}</td>
        <td></td>
      </tr>
    `;
    actualizarTotalFinal();
  } catch (error) {
    console.error("Error al obtener los cargos:", error);
  }
}


async function insertarCargo() {
  const descripcion = document.getElementById('descripcion').value;
  const monto = document.getElementById('monto').value;
  if (!monto || !descripcion) {
    Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
    return;
  }

  const response = await fetch('http://localhost/adamanda/api_rest_hotel/cargos', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      id_reservacion: reservaId,
      descripcion: descripcion,
      monto: monto,

    })
  });

  const responsejson = await response.json();
  console.log("Respuesta en JSON: ", responsejson);

  if (responsejson.ban === 0) {

    actualizarCargosAdicionales();
    Swal.fire({
      toast: true, position: 'top-end', icon: 'success', title: 'Cargo agregado correctamente', showConfirmButton: false, timer: 3000
    });
    $('#modalAgregarCargo').modal('hide');
    document.getElementById('formAgregarCargo').reset();

  }
  else {
    Swal.fire('Error al agregar el cargo', '', 'error');
  }
}

async function eliminarCargo(id) {
  const resultado = await Swal.fire({
    title: "¿Estás seguro?",
    text: "Esta acción no se puede deshacer",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  });

  if (resultado.isConfirmed) {
    try {
      const response = await fetch(`http://localhost/adamanda/api_rest_hotel/cargos/${id}`, {
        method: "DELETE"
      });

      if (response.ok) {
        const data = await response.json();
        if (data.ban === 0) {
          Swal.fire("Eliminado", "El cargo ha sido eliminado.", "success");
          actualizarCargosAdicionales();

        } else if (data.ban === 1) {
          Swal.fire("Error", "No se encuentra el cargo a eliminar", "error");
        }
      }
    } catch (error) {
      console.error(error);
    }
  }
}

async function registrarInventarioFaltante() {
  faltantes = [];
  const filas = document.querySelectorAll("#tablaArticulos tbody tr");
  filas.forEach((fila) => {
    const id = fila.children[0].textContent;
    const input = document.getElementById(`faltante-${id}`);
    const cantidad = parseInt(input.value);

    if (cantidad > 0) {
      faltantes.push({
        id_habitacion: habitacionId,
        id_reservacion: reservaId,
        id_articulo: id,
        cantidad: cantidad
      });
    }
  });

  console.log(JSON.stringify(faltantes));

  if (faltantes.length === 0) {
    return 0; // Nada que registrar
  }
  else {

    const response = await fetch('http://localhost/adamanda/api_rest_hotel/inventario_faltante', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body:
        JSON.stringify(faltantes)
    });

    const data = await response.json();
    console.log(data);

  }

}

async function generarFactura() {
const tipo_pago = document.getElementById("tipoPago").value;

 await fetch('http://localhost/adamanda/api_rest_hotel/check_out_reservacion',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body:
       JSON.stringify({
        id_reservacion: reservaId,
        tipo_pago: tipo_pago,
                       })
    });
}

async function terminarCheckOut() {

  Swal.fire({
    title: '¿Listo para terminar la reservación?',
    text: 'Clic en aceptar, o cancelar para revisión',
    icon: 'info',
    showCancelButton: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    backdrop: true,
    confirmButtonText: 'Sí, terminar',
    cancelButtonText: 'No, cancelar'
  }).then((result) => {
    if (result.isConfirmed) {

      console.log('Ejecutando acción...');
      registrarInventarioFaltante();
      generarFactura();
      window.location.href = 'salidas.php';
    }
  });

}

document.addEventListener("DOMContentLoaded", function () {
  obtenerInfoHab();
  cargarDatos();
  obtenerInfoReservacion();
  obtenerInventario();
  actualizarCargosAdicionales();
  document.getElementById('btnAgregarCargo').addEventListener('click', insertarCargo);
  document.getElementById('btnTerminar').addEventListener('click', terminarCheckOut);
});