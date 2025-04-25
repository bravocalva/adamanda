// Obtener el ID de la URL
const urlParams = new URLSearchParams(window.location.search);
const habitacionId = urlParams.get('id');

// Función para obtener los datos de la habitación
async function obtenerHabitacion() {
  try {
    const response = await fetch(`http://localhost/adamanda/api_rest_hotel/habitaciones_info/${habitacionId}`);
    const habitacion = await response.json();

    // Depuración: Verificar que estamos obteniendo los datos correctos
    console.log('Datos de la habitación:', habitacion);

    return habitacion;
  } catch (error) {
    console.error('Error al obtener los datos:', error);
    return null;  // Devolver null en caso de error
  }
}

// Función para obtener los artículos del inventario
async function obtenerInventario() {
  try {
    console.log("id desde inventario: ", habitacionId);
    const response = await fetch(`http://localhost/adamanda/api_rest_hotel/inventario_hab/${habitacionId}`);
    const articulos = await response.json();

    const tbody = document.querySelector("#tablaArticulos tbody");
    tbody.innerHTML = ""; // Limpiar contenido anterior

    articulos.forEach(item => {
      const tr = document.createElement("tr");

      const columnas = [
        item.id_articulo,
        item.nombre_articulo,
        item.descripcion_articulo,
        item.precio,
        item.stock_actual
      ];

      columnas.forEach(valor => {
        const td = document.createElement("td");
        td.textContent = valor;
        tr.appendChild(td);
      });

      tbody.appendChild(tr);
    });
  } catch (error) {
    console.error('Error al obtener los datos:', error);
    return null;  // Devolver null en caso de error
  }
}

function cargarArticulos() {
  fetch('http://localhost/adamanda/api_rest_hotel/articulo')
    .then(response => response.json())
    .then(data => {
      const select = document.getElementById('selectArticulo');
      select.innerHTML = '<option value="">Selecciona un artículo...</option>';
      data.forEach(articulo => {
        const option = document.createElement('option');
        option.value = articulo.id_articulo;
        option.textContent = `${articulo.nombre} - ${articulo.descripcion}`;
        select.appendChild(option);
      });
    })
    .catch(error => console.error('Error al cargar los artículos:', error));
}

function cargarArticulosQuitar() {
  fetch(`http://localhost/adamanda/api_rest_hotel/inventario_hab/${habitacionId}`)
    .then(response => response.json())
    .then(data => {
      const select = document.getElementById('selectArticulo_Q');
      select.innerHTML = '<option value="">Selecciona un artículo...</option>';
      data.forEach(articulo => {
        const option = document.createElement('option');
        option.value = articulo.id_articulo;
        option.textContent = `${articulo.nombre_articulo} - ${articulo.descripcion_articulo}`;
        option.setAttribute('data-stock', articulo.stock_actual);
        select.appendChild(option);
      });
    })
    .catch(error => console.error('Error al cargar los artículos:', error));
}


async function cargarDatosHabitacion() {
  const habitacion = await obtenerHabitacion();
  console.log('Cargando datos en el HTML...');

  // Card de Información de la habitación
  $('#info-habitacion-id').text(habitacion.id_habitacion);
  $('#info-habitacion-numero').text(habitacion.numero);
  $('#header-number').text(habitacion.numero);
  $('#info-habitacion-tipo').text(habitacion.tipo_habitacion);
  $('#info-habitacion-estado').html(`<span class="badge badge-${habitacion.estado === 'DISPONIBLE' ? 'success' 
    : habitacion.estado === 'OCUPADO' ? 'danger' 
    : habitacion.estado === 'LIMPIEZA' ? 'info' 
    : habitacion.estado === 'RESERVADO' ? 'warning' 
    : 'secondary'}">${habitacion.estado}</span>`);

  // Card de Imagen y Descripción
  $('#info-habitacion-imagen').attr('src', habitacion.ruta_imagen);
  $('#info-habitacion-descripcion').text(habitacion.descripcion);
}

async function InsertarArticulo() {
  const articuloId = document.getElementById('selectArticulo').value;
  const cantidad = document.getElementById('cantidad').value;

  if (!articuloId || !cantidad) {
    alert('Por favor selecciona un artículo y proporciona una cantidad.');
    return;
  }

  const datos = {
    id_articulo: articuloId,
    cantidad: cantidad,
    id_habitacion: habitacionId
  };

  try {
    const response = await fetch('http://localhost/adamanda/api_rest_hotel/inventario_hab', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datos)
    });

    const resultado = await response.json();

    if (response.ok) {
      
      Swal.fire({
        toast: true,  position: 'top-end', icon: 'success', title: 'Artículo agregado correctamente', showConfirmButton: false, timer: 3000
      });

      $('#modalAgregar').modal('hide');
      document.getElementById('formAgregarArticulo').reset();
      $('#selectArticulo').val(null).trigger('change');
      obtenerInventario();
    } else {
      console.error('Error al insertar:', resultado);
      alert('Hubo un error al insertar el artículo.');
    }
  } catch (error) {
    console.error('Error de red al insertar artículo:', error);
    alert('No se pudo conectar con el servidor.');
  }
}

async function RemoverArtculo() {
  const articuloId = document.getElementById('selectArticulo_Q').value;
  const cantidad = document.getElementById('cantidad_Q').value;
  const stockActual =document.getElementById('stockActual').value;

  if (!articuloId || !cantidad) {
    alert('Por favor selecciona un artículo y proporciona una cantidad.');
    return;
  }
  if(cantidad>stockActual){
    alert('Por favor proporciona una cantidad válida.');
    return;
  }

  const datos = {
    id_articulo: articuloId,
    cantidad: cantidad,
    id_habitacion: habitacionId
  };

  try {
    const response = await fetch('http://localhost/adamanda/api_rest_hotel/inventario_hab', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datos)
    });

    const resultado = await response.json();

    if (response.ok) {
      
      Swal.fire({
        toast: true,  position: 'top-end', icon: 'success', title: 'Artículo removido correctamente', showConfirmButton: false, timer: 3000, timerProgressBar: true
      });

      $('#modalquitar').modal('hide');
      document.getElementById('formQuitarArticulo').reset();
      $('#selectArticulo_Q').val(null).trigger('change');
      obtenerInventario();
    } else {
      console.error('Error al remover:', resultado);
      alert('Hubo un error al remover el artículo.');
    }
  } catch (error) {
    console.error('Error de red al insertar artículo:', error);
    alert('No se pudo conectar con el servidor.');
  }
}


window.addEventListener('DOMContentLoaded', async () => {
  cargarArticulos();
  await obtenerInventario();
  if (habitacionId) {
    await cargarDatosHabitacion();
  } else {
    console.error('No se encontró ID en la URL');
  }

  $('#selectArticulo').select2({
    dropdownParent: $('#modalAgregar'),
    width: '100%'
  });
  document.getElementById('quitarBtn').addEventListener('click',cargarArticulosQuitar);
  document.getElementById('btnAgregarArticulo').addEventListener('click', InsertarArticulo);
  document.getElementById('btnQuitarArticulo').addEventListener('click', RemoverArtculo);
  document.getElementById('selectArticulo_Q').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const stock = selectedOption.getAttribute('data-stock');
    document.getElementById('stockActual').value = stock || ''; // Si no hay stock, se limpia
  });

});



