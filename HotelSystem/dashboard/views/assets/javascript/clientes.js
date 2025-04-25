let dataTable;
const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const regexCURP = /^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z\d]{2}$/;
const API_BASE = 'http://localhost/adamanda/api_rest_hotel/cliente';

async function obtenerDatos() {
    try {
        const response = await fetch(API_BASE);
        if (!response.ok) throw new Error('Error al obtener los datos');
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'No se pudieron obtener los datos', 'error');
        return [];
    }
}

function actualizarDataTable(datos) {
    const tableConfig = {
        "paging": true,
        "pageLength": 5,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "language": {
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
            },
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "search": "Buscar:"
        },
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "data": datos,
        "columns": [
            { "data": "id_cliente" },
            { "data": "curp" },
            { "data": "nombre" },
            { "data": "apellido_p" },
            { "data": "apellido_m" },
            { "data": "telefono" },
            { "data": "correo" },
            { 
                "data": "id_cliente",
                "render": function(data) {
                    return `
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${data}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${data}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    };

    if (!dataTable) {
        dataTable = $('#clientesTable').DataTable(tableConfig);
    } else {
        dataTable.clear().rows.add(datos).draw();
    }
}

async function actualizarTabla() {
    const datos = await obtenerDatos();
    if (datos.length > 0) {
        actualizarDataTable(datos);
        document.getElementById('clientesTable').style.display = 'table';
    } else {
        console.warn('No hay datos para mostrar en la tabla.');
    }
}

async function cargarPagina() {
    document.getElementById('loading').style.display = 'block';
    await actualizarTabla();
    document.getElementById('loading').style.display = 'none';
    setInterval(actualizarTabla, 10000);
}

async function cargarDatosCliente(id) {
    try {
        const response = await fetch(`${API_BASE}/${id}`);
        if (!response.ok) throw new Error("Error al obtener los datos del cliente");
        
        const cliente = await response.json();
        document.getElementById('curp_act').value = cliente.curp;
        document.getElementById('nombre_act').value = cliente.nombre;
        document.getElementById('apellido_paterno_act').value = cliente.apellido_p;
        document.getElementById('apellido_materno_act').value = cliente.apellido_m;
        document.getElementById('telefono_act').value = cliente.telefono;
        document.getElementById('email_act').value = cliente.correo;
        
        document.getElementById('modalActualizarCliente').setAttribute('data-id', id);
        $('#modalActualizarCliente').modal('show');

    } catch (error) {
        console.error("Error al cargar cliente:", error);
        Swal.fire("Error", "No se pudieron cargar los datos del cliente.", "error");
    }
}

function validarCampos({ curp, nombre, apellido_p, apellido_m, telefono, correo }) {
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

    if (!validarCampos(formData)) return;

    try {
        const response = await fetch(API_BASE, {
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
                await actualizarTabla();
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

async function actualizarCliente() {
    const idCliente = document.getElementById('modalActualizarCliente').getAttribute("data-id");
    const formData = {
        curp: document.getElementById('curp_act').value,
        nombre: document.getElementById('nombre_act').value,
        apellido_p: document.getElementById('apellido_paterno_act').value,
        apellido_m: document.getElementById('apellido_materno_act').value,
        telefono: document.getElementById('telefono_act').value,
        correo: document.getElementById('email_act').value,
        id: idCliente
    };

    if (!validarCampos(formData)) return;

    try {
        const response = await fetch(API_BASE, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            const data = await response.json();
            if (data.ban === 0) {
                Swal.fire('Éxito', 'Cliente actualizado correctamente', 'success');
                $('#modalActualizarCliente').modal('hide');
                await actualizarTabla();
            } else {
                Swal.fire('Error', data.msg || 'Problema al actualizar', 'error');
            }
        } else {
            Swal.fire('Error', 'Hubo un problema con la solicitud', 'error');
        }
    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Hubo un problema al enviar los datos', 'error');
    }
}

async function eliminarCliente(id) {
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
            const response = await fetch(`${API_BASE}/${id}`, { method: "DELETE" });
            if (response.ok) {
                const data = await response.json();
                if (data.ban === 0) {
                    Swal.fire("Eliminado", "El cliente ha sido eliminado.", "success");
                    await actualizarTabla();
                } else {
                    Swal.fire("Error", data.msg || "No se pudo eliminar", "error");
                }
            } else {
                Swal.fire("Error", "No se pudo eliminar el cliente.", "error");
            }
        } catch (error) {
            console.error("Error al eliminar cliente:", error);
            Swal.fire("Error", "Ocurrió un problema al eliminar", "error");
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    cargarPagina();
    
    document.getElementById('btnGuardarCliente').addEventListener('click', agregarCliente);
    document.getElementById('btnActualizarCliente').addEventListener('click', actualizarCliente);

    document.querySelector("#clientesTable tbody").addEventListener("click", function(event) {
        const btn = event.target.closest("button");
        if (!btn) return;
        
        const idCliente = btn.getAttribute("data-id");
        if (btn.classList.contains("delete-btn")) {
            eliminarCliente(idCliente);
        } else if (btn.classList.contains("edit-btn")) {
            cargarDatosCliente(idCliente);
        }
    });
});