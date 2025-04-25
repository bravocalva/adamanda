const urlParams = new URLSearchParams(window.location.search);
const habitacionId = urlParams.get('room_id');
const fecha_entrada = urlParams.get('fecha_e');
const fecha_salida = urlParams.get('fecha_s');
const noches = calcularNoches(fecha_entrada, fecha_salida);
const $inputA = $('#adelanto');
const $inputB = $('#total');
const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const regexCURP = /^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z\d]{2}$/;

function calcularNoches(fechaEntrada, fechaSalida) {
    const entrada = new Date(fechaEntrada);
    const salida = new Date(fechaSalida);

    // Calcula la diferencia en milisegundos
    const diferenciaMs = salida - entrada;

    // Milisegundos por día
    const msPorDia = 1000 * 60 * 60 * 24;

    // Retorna la cantidad de noches
    return Math.round(diferenciaMs / msPorDia);
}

async function cargarClientes() {
    fetch('http://localhost/adamanda/api_rest_hotel/cliente')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('cliente_id');
            select.innerHTML = '<option value="0">Selecciona un cliente...</option>';
            data.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.id_cliente;
                option.textContent = `${cliente.curp} - ${cliente.nombre} ${cliente.apellido_p} ${cliente.apellido_m}`;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar los clientes:', error));
}

async function obtenerInfoHab() {
    const response = await fetch(`http://localhost/adamanda/api_rest_hotel/habitaciones_info/${habitacionId}`);
    const habitacion = await response.json();

    // Depuración: Verificar que estamos obteniendo los datos correctos
    console.log('Datos de la habitación:', habitacion);

    return habitacion;
}
async function cargarDatosHab() {
    const habitacion = await obtenerInfoHab();
    // Card de Información de la habitación
    const total = (parseFloat(noches) * parseFloat(habitacion.precio));
    $('#info-habitacion-id').text(habitacion.id_habitacion);
    $('#info-habitacion-numero').text(habitacion.numero);
    $('#header-number').text(habitacion.numero);
    $('#info-habitacion-tipo').text(habitacion.tipo_habitacion);

    $('#fecha_entrada').text(fecha_entrada);
    $('#fecha_salida').text(fecha_salida);

    // Card de Imagen y Descripción
    $('#info-habitacion-imagen').attr('src', habitacion.ruta_imagen);
    $('#info-habitacion-descripcion').text(habitacion.descripcion);
    $('#id_precio').text(habitacion.precio);
    $('#id_noches').text(noches);
    $('#total').val(total.toFixed(2));
    $('#total_pagar').val(total.toFixed(2));

}

function actualizarTotalPagar() {
    const total = parseFloat($('#total').val()) || 0;
    const adelanto = parseFloat($('#adelanto').val()) || 0;
    const totalPagar = total - adelanto;

    $('#total_pagar').val(totalPagar >= 0 ? totalPagar.toFixed(2) : '0.00');
}

function seleccionarCliente(formData) {
    const select = document.getElementById('cliente_id');

    // Recorremos las opciones del select
    for (let i = 0; i < select.options.length; i++) {
        const option = select.options[i];
        // Comparamos los datos del cliente agregado con los ya existentes
        if (option.textContent.includes(formData.curp) && 
            option.textContent.includes(formData.nombre) && 
            option.textContent.includes(formData.apellido_p)) {
            // Si encontramos la coincidencia, lo seleccionamos
            select.value = option.value;
            $('#cliente_id').trigger('change'); // Si usas select2, actualizamos visualmente
            break;
        }
    }
}

function validarCamposModal({ curp, nombre, apellido_p, apellido_m, telefono, correo }) {
    if (!curp || !nombre || !apellido_p || !apellido_m || !telefono || !correo) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return false;
    }
    if (!regexCURP.test(curp)) {
        Swal.fire('Error', 'Formato de CURP invalido', 'error');
        return false;
    }
    if (!regex.test(correo)) {
        Swal.fire('Error', 'Formato de correo invalido', 'error');
        return false;
    }
    return true;
}

async function agregarCliente() {
    const formData = {
        curp: document.getElementById('curp').value,
        nombre: document.getElementById('nombre').value,
        apellido_p: document.getElementById('apellido_paterno').value,
        apellido_m: document.getElementById('apellido_materno').value,
        correo: document.getElementById('email').value,
        telefono: document.getElementById('telefono').value
    };

    if (!validarCamposModal(formData)) return;

    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/cliente', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            const data = await response.json();
            if (data.ban === 0) {
                Swal.fire('Éxito', 'Cliente agregado correctamente', 'success');
                $('#modalAgregarCliente').modal('hide');
                $('#formAgregarCliente')[0].reset();
                cargarClientes();
                setTimeout(() => seleccionarCliente(formData), 300); // Esperamos a que se carguen los clientes
            } else {
                Swal.fire('Error', data.msg || 'Problema con el registro', 'error');
            }
        } else {
            Swal.fire('Error', 'Hubo un problema con la solicitud', 'error');
        }
    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Hubo un problema al enviar los datos', 'error');
    }
}

async function GenerarHospedaje(){
    const id_cliente = document.getElementById('cliente_id').value; //Value para input
    let adelanto = document.getElementById('adelanto').value; //Value para input
    const id_usuario = parseInt(document.getElementById('id_usuario').textContent);

    if(id_cliente === '0'){

        Swal.fire('Error', 'Selecciona un cliente', 'error'); 
        return false;
    }
    if (adelanto === "") {
        adelanto = 0;
    }

    const datos = {
        fecha_entrada: fecha_entrada,
        fecha_salida: fecha_salida,
        adelanto: adelanto,
        usuario_id: id_usuario,
        cliente_id: id_cliente,
        habitacion_id: habitacionId
    };

    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/hospedar_inm', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        });

        const resultado = await response.json();
        console.log('Respuesta del servidor:', resultado);

        if (resultado.ban === 0) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: resultado.msg,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'reservar.php';
            });
        } else {
            Swal.fire('Advertencia', resultado.msg, 'warning');
        }

    } catch (error) {
        console.error('Error en la solicitud:', error);
        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
    }


}


document.addEventListener("DOMContentLoaded", function () {

    obtenerInfoHab();
    cargarClientes();
    $('#cliente_id').select2({
        width: '100%'
    });
    cargarDatosHab();

    $('#adelanto').on('input', function () {
        actualizarTotalPagar();
    });
    document.getElementById('btnGuardarCliente').addEventListener('click', agregarCliente);
    document.getElementById('btnRegistrarRerserva').addEventListener('click', GenerarHospedaje);
});

