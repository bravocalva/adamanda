let dataTable;
async function obtenerDatos() {
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/articulo');
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
        "pageLength": 10,
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
            { "data": "id_articulo" }, { "data": "nombre" },{ "data": "descripcion" },{ "data": "precio" },
            { 
                "data": "id_articulo",
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
        dataTable = $('#articuloTable').DataTable(tableConfig);
    } else {
        dataTable.clear().rows.add(datos).draw();
    }
}

async function actualizarTabla() {
    const datos = await obtenerDatos();
    if (datos.length > 0) {
        actualizarDataTable(datos);
        document.getElementById('articuloTable').style.display = 'table';
    } else {
        console.warn('No hay datos para mostrar en la tabla.');
    }
}

async function cargarPagina() {
    document.getElementById('loading').style.display = 'block';
    await actualizarTabla();
    document.getElementById('loading').style.display = 'none';
}

async function agregarArticulo() {
    const nombre = document.getElementById('nombre').value;
    const descripcion = document.getElementById('descripcion').value;
    const precio = document.getElementById('precio').value;
    
    if (!nombre || !descripcion || !precio) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/articulo', {
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
                Swal.fire('Éxito', 'Articulo agregado correctamente', 'success');
                $('#modalAgregarArticulo').modal('hide');
                $('#formAgregarArticulo')[0].reset();
                actualizarTabla();
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

async function eliminar(id) {
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
            const response = await fetch(`http://localhost/adamanda/api_rest_hotel/articulo/${id}`, {
                method: "DELETE"
            });

            if (response.ok) {
                const data = await response.json();
                if (data.ban === 0) {
                    Swal.fire("Eliminado", "El articulo ha sido eliminado.", "success");
                    actualizarTabla();
                } else {
                    Swal.fire("Error", "No se encuentra el articulo a eliminar", "error");
                }
            } else {
                Swal.fire("Error", "No se pudo eliminar el articulo.", "error");
            }
        } catch (error) {
            console.error("Error al eliminar articulo:", error);
            Swal.fire("Error", "Ocurrió un problema al eliminar el articulo.", "error");
        }
    }
}

async function cargarDatosArticulo(id) {
    try {
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/articulo/${id}`);
        if (!response.ok) throw new Error("Error al obtener los datos el articulo");

        const articulo = await response.json();
        console.log(articulo);

        // Llenar el formulario con los datos del articulo
        document.getElementById('nombre_act').value = articulo.nombre;
        document.getElementById('descripcion_act').value = articulo.descripcion;
        document.getElementById('precio_act').value = articulo.precio;

        // Guardar el ID del articulo en un atributo oculto
        document.getElementById('modalActualizarArticulo').setAttribute('data-id', id);
        // Abrir el modal
        $('#modalActualizarArticulo').modal('show');

    } catch (error) {
        console.error("Error al cargar articulo:", error);
        Swal.fire("Error", "No se pudieron cargar los datos del articulo.", "error");
    }
}

async function actualizarArticulo() {
    const idArticulo = document.getElementById('modalActualizarArticulo').getAttribute("data-id");
    const nombre = document.getElementById('nombre_act').value;
    const descripcion = document.getElementById('descripcion_act').value;
    const precio = document.getElementById('precio_act').value;

    if (!nombre || !descripcion || !precio) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    try {
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/articulo/${idArticulo}`, {
            method: 'PUT',
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
                Swal.fire('Éxito', 'Tipo de articulo actualizado correctamente', 'success');
                $('#modalActualizarArticulo').modal('hide');
                actualizarTabla();
            } 
            else if (data.ban === 1) {
                Swal.fire('Error',data.msg, 'error');
            } 
            else if (data.ban === 2) {
                Swal.fire('Error',data.msg, 'error');
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


document.addEventListener('DOMContentLoaded', function() {
    cargarPagina();
    document.getElementById('btnGuardarArticulo').addEventListener('click', agregarArticulo);
    document.getElementById('btnActualizarArticulo').addEventListener('click', actualizarArticulo);

    document.querySelector("#articuloTable tbody").addEventListener("click", function (event) {
        if (event.target.closest(".delete-btn")) {
            const idArticulo = event.target.closest(".delete-btn").getAttribute("data-id");
            eliminar(idArticulo);
        }
    });

    document.querySelector("#articuloTable tbody").addEventListener("click", function (event) {
        if (event.target.closest(".edit-btn")) {
            const idArticulo = event.target.closest(".edit-btn").getAttribute("data-id");
            cargarDatosArticulo(idArticulo);
        }
    });
    
});