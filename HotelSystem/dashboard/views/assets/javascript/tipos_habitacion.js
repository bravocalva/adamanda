let dataTable;

async function obtenerTiposHabitacion() {
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/tipohabitacion');
        if (!response.ok) {
            throw new Error('Error al obtener los datos');
        }
        let data = await response.json();
        console.log(data);
        return data;
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'No se pudieron obtener los datos', 'error');
        return [];
    }
}

function actualizarDataTableTipos(datos) {
    if (!dataTable) {
        dataTable = $('#tiposHabitacionTable').DataTable({
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
            "data": datos, // Asignar datos directamente
            "columns": [{
                "data": "id_tipo_hab"
            },
            {
                "data": "nombre"
            },
            {
                "data": "descripcion"
            },
            {
                "data": "precio"
            },
            {
                "data": "id_tipo_hab",
                "render": function (data) {
                    return `
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${data}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${data}"><i class="fas fa-trash"></i></button>
                    `;
                }
            }]
        });
    } else {
        dataTable.clear().rows.add(datos).draw();
    }
}

async function actualizarTablaTipos() {
    const datos = await obtenerTiposHabitacion();

    if (datos.length === 0) {
        console.warn('No hay datos para mostrar en la tabla.');
        return;
    }
    actualizarDataTableTipos(datos);
    document.getElementById('tiposHabitacionTable').style.display = 'table';
}

async function cargarPaginaTipos() {
    document.getElementById('loading').style.display = 'block';
    await actualizarTablaTipos();
    document.getElementById('loading').style.display = 'none';
    setInterval(async () => {
        try {
            await actualizarTablaTipos();
        } catch (error) {
            console.error('Error en la actualización automática:', error);
        }
    }, 10000);
}

async function cargarDatosTipoHabitacion(idTipoHabitacion) {
    try {
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/tipohabitacion/${idTipoHabitacion}`);
        if (!response.ok) throw new Error("Error al obtener los datos del tipo de habitación");

        const tipoHabitacion = await response.json();
        console.log(tipoHabitacion);

        // Llenar el formulario con los datos del tipo de habitación
        document.getElementById('nombre_act').value = tipoHabitacion.nombre;
        document.getElementById('descripcion_act').value = tipoHabitacion.descripcion;
        document.getElementById('precio_act').value = tipoHabitacion.precio;

        // Guardar el ID del tipo de habitación en un atributo oculto
        document.getElementById('modalActualizarTipoHabitacion').setAttribute('data-id', idTipoHabitacion);
        // Abrir el modal
        $('#modalActualizarTipoHabitacion').modal('show');

    } catch (error) {
        console.error("Error al cargar tipo de habitación:", error);
        Swal.fire("Error", "No se pudieron cargar los datos del tipo de habitación.", "error");
    }
}

async function agregarTipoHabitacion() {
    const nombre = document.getElementById('nombre').value;
    const descripcion = document.getElementById('descripcion').value;
    const precio = document.getElementById('precio').value;

    if (!nombre || !descripcion || !precio) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/tipohabitacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombre,
                descripcion: descripcion,
                precio: precio
            })
        });

        if (response) {
            const data = await response.json();

            if (data.ban === 0) {
                Swal.fire('Éxito', 'Tipo de habitación agregado correctamente', 'success');
                $('#modalAgregarTipoHabitacion').modal('hide');
                $('#formAgregarTipoHabitacion')[0].reset();
                actualizarTablaTipos();
            } 
            else if (data.ban === 1) {
                Swal.fire('Error', 'El nombre ya esta registrado', 'error');
            } 
            
            else {
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

async function eliminarTipoHabitacion(id) {
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
            const response = await fetch(`http://localhost/adamanda/api_rest_hotel/tipohabitacion/${id}`, {
                method: "DELETE"
            });

            if (response.ok) {
                const data = await response.json();
                if (data.ban === 0) {
                    Swal.fire("Eliminado", "El tipo de habitación ha sido eliminado.", "success");
                    actualizarTablaTipos();
                } else {
                    Swal.fire("Error", "No se encuentra el tipo de habitación a eliminar", "error");
                }
            } else {
                Swal.fire("Error", "No se pudo eliminar el tipo de habitación.", "error");
            }
        } catch (error) {
            console.error("Error al eliminar tipo de habitación:", error);
            Swal.fire("Error", "Ocurrió un problema al eliminar el tipo de habitación.", "error");
        }
    }
}

async function actualizarTipoHabitacion() {
    const idTipoHabitacion = document.getElementById('modalActualizarTipoHabitacion').getAttribute("data-id");
    const nombre = document.getElementById('nombre_act').value;
    const descripcion = document.getElementById('descripcion_act').value;
    const precio = document.getElementById('precio_act').value;

    if (!nombre || !descripcion || !precio) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    try {
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/tipohabitacion`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: idTipoHabitacion,
                nombre: nombre,
                descripcion: descripcion,
                precio: precio
            })
        });

        if (response) {
            const data = await response.json();
            if (data.ban === 0) {
                Swal.fire('Éxito', 'Tipo de habitación actualizado correctamente', 'success');
                $('#modalActualizarTipoHabitacion').modal('hide');
                actualizarTablaTipos();
            } 
            else if (data.ban === 1) {
                Swal.fire('Error',"No existe el ID", 'error');
            } 
            else if (data.ban === 2) {
                Swal.fire('Error','El nombre ingresado ya existe', 'error');
            } 
            else {
                Swal.fire('Error', 'Hubo un problema al actualizar el tipo de habitación', 'error');
            }
        } else {
            Swal.fire('Error', 'Hubo un problema con la solicitud', 'error');
        }
    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Hubo un problema al enviar los datos', 'error');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    cargarPaginaTipos();
    document.getElementById('btnGuardarTipoHabitacion').addEventListener('click', agregarTipoHabitacion);
    document.getElementById('btnActualizarTipoHabitacion').addEventListener('click', actualizarTipoHabitacion);

    document.querySelector("#tiposHabitacionTable tbody").addEventListener("click", function (event) {
        if (event.target.closest(".delete-btn")) {
            const idTipoHabitacion = event.target.closest(".delete-btn").getAttribute("data-id");
            eliminarTipoHabitacion(idTipoHabitacion);
        }
    });
    document.querySelector("#tiposHabitacionTable tbody").addEventListener("click", function (event) {
        if (event.target.closest(".edit-btn")) {
            const idTipoHabitacion = event.target.closest(".edit-btn").getAttribute("data-id");
            cargarDatosTipoHabitacion(idTipoHabitacion);
        }
    });
});
