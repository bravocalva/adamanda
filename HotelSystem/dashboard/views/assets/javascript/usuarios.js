let dataTable;
const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

async function obtenerDatos() {
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/usuarios');
        if (!response.ok) {
            throw new Error('Error al obtener los datos');
        }
        let data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'No se pudieron obtener los datos', 'error');
        return [];
    }
}

function actualizarDataTable(datos) {
    if (!dataTable) {
        dataTable = $('#usuariosTable').DataTable({
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
                "data": "id_usuario"
            },
            {
                "data": "nombre_completo"
            },
            {
                "data": "correo"
            },
            {
                "data": "Rol"
            },
            {
                "data": "id_usuario",
                "render": function (data) {
                    return `
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${data}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${data}"><i class="fas fa-trash"></i></button>
                            `;
                }
            }
            ]
        });
    } else {
        dataTable.clear().rows.add(datos).draw();
    }
}

async function actualizarTabla() {
    const datos = await obtenerDatos();

    if (datos.length === 0) {
        console.warn('No hay datos para mostrar en la tabla.');
        return;
    }
    actualizarDataTable(datos);
    document.getElementById('usuariosTable').style.display = 'table';
}

async function cargarPagina() {
    document.getElementById('loading').style.display = 'block';
    await actualizarTabla();
    document.getElementById('loading').style.display = 'none';
    setInterval(async () => {
        try {
            await actualizarTabla();
        } catch (error) {
            console.error('Error en la actualización automática:', error);
        }
    }, 5000);
}

async function cargarRoles() {
    const selectRol = document.getElementById('rol'); // Capturamos el combo box

    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/roles'); // URL de la API
        if (!response.ok) throw new Error('Error al obtener roles');

        const roles = await response.json(); // Convertir respuesta a JSON

        // Limpiar opciones previas
        selectRol.innerHTML = '<option value="0">Seleccione un rol</option>';

        // Recorrer los datos y agregarlos al combo box
        roles.forEach(rol => {
            const option = document.createElement('option');
            option.value = rol.id_rol; // ID del rol
            option.textContent = rol.nombre; // Nombre del rol
            selectRol.appendChild(option);
        });

    } catch (error) {
        console.error('Error al cargar roles:', error);
        selectRol.innerHTML = '<option value="">Error al cargar roles</option>';
    }
}

async function actualizarUsuario() {
    // Recuperar los valores del formulario
    const idUsuario = document.getElementById('modalAgregarUsuario').getAttribute('data-id');
    const nombre = document.getElementById('nombre').value;
    const apellidoPaterno = document.getElementById('apellido_paterno').value;
    const apellidoMaterno = document.getElementById('apellido_materno').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const rol = document.getElementById('rol').value;

    // Verificar si alguno de los campos está vacío
    if (!nombre || !apellidoPaterno || !apellidoMaterno || !email || !rol || !password) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return; // Detener la ejecución de la función
    }

    if (!regex.test(email)) {
        Swal.fire('Error', 'Formato de correo invalido', 'error');
        return;
    }

    else if (rol == "0") {
        Swal.fire('Error', 'Selecciona un Rol', 'error');
        return;
    }

    try {
        // Realizar la solicitud PUT
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/usuarios/${idUsuario}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombre,
                apellido_p: apellidoPaterno,
                apellido_m: apellidoMaterno,
                email: email,
                password: password, // Si no se cambia la contraseña, este campo puede estar vacío
                rol: rol
            })
        });

        if (response.ok) {
            // Leer la respuesta como JSON
            const data = await response.json();

            // Mostrar mensaje de acuerdo con la bandera 'ban'
            if (data.ban === 0) {
                // Actualización exitosa
                Swal.fire('Éxito', 'Usuario actualizado correctamente', 'success');
                $('#modalAgregarUsuario').modal('hide'); // Cerrar el modal
                actualizarTabla(); // Actualizar la tabla si es necesario
            } 
            else if (data.ban === 2) {
                // Error de actualización (correo ya registrado o algún otro error)
                Swal.fire('Error', data.msg || 'Correo ya registrado', 'error');
            }
        } else {
            
            Swal.fire('Error', 'Hubo un problema con la solicitud', 'error');
        }
    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Hubo un problema al enviar los datos', 'error');
    }
}


async function agregarUsuario() {
    // Recuperar los valores del formulario
    const nombre = document.getElementById('nombre').value;
    const apellidoPaterno = document.getElementById('apellido_paterno').value;
    const apellidoMaterno = document.getElementById('apellido_materno').value;
    const email = document.getElementById('email').value;
    const email_ver = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const rol = document.getElementById('rol').value;


    // Verificar si alguno de los campos está vacío
    if (!nombre || !apellidoPaterno || !apellidoMaterno || !email || !password || !rol) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return; // Detener la ejecución de la función   
    }
    if (!regex.test(email_ver)) {
        Swal.fire('Error', 'Formato de correo invalido', 'error');
        return;
    }
    else if (rol == "0") {
        Swal.fire('Error', 'Selecciona un Rol', 'error');
        return;
    }

    try {
        // Realizar la solicitud POST
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/usuarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombre,
                apellido_p: apellidoPaterno,
                apellido_m: apellidoMaterno,
                email: email,
                password: password,
                rol: rol
            })
        });

        if (response.ok) {
            // Leer la respuesta como JSON
            const data = await response.json();

            // Mostrar mensaje de acuerdo con la bandera 'ban'
            if (data.ban === 0) {
                // Registro exitoso
                Swal.fire('Éxito', 'Usuario agregado correctamente', 'success');
                $('#modalAgregarUsuario').modal('hide'); // Cerrar el modal
                actualizarTabla(); // Actualizar la tabla si es necesario
            } else {
                // Error de registro (correo ya registrado o algún otro error)
                Swal.fire('Error', data.msg || 'Hubo un problema al agregar el usuario', 'error');
            }
        } else {
            // Si la respuesta de la API no es 200
            Swal.fire('Error', 'Hubo un problema con la solicitud', 'error');
        }
    } catch (error) {
        // Capturar cualquier error en la solicitud
        console.error(error);
        Swal.fire('Error', 'Hubo un problema al enviar los datos', 'error');
    }
}

async function eliminarUsuario(id) {
    // Mostrar mensaje de confirmación con SweetAlert
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

    // Si el usuario confirma
    if (resultado.isConfirmed) {
        try {
            // Hacer la petición DELETE
            const response = await fetch(`http://localhost/adamanda/api_rest_hotel/usuarios/${id}`, {
                method: "DELETE"
            });

            // Verificar si la petición fue exitosa
            if (response.ok) {
                Swal.fire("Eliminado", "El usuario ha sido eliminado.", "success");
                actualizarTabla(); // Recargar la tabla para reflejar el cambio
            } else {
                Swal.fire("Error", "No se pudo eliminar el usuario.", "error");
            }
        } catch (error) {
            console.error("Error al eliminar usuario:", error);
            Swal.fire("Error", "Ocurrió un problema al eliminar el usuario.", "error");
        }
    }
}


async function cargarDatosUsuario(idUsuario) {
    try {
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/usuarios/${idUsuario}`);
        if (!response.ok) throw new Error("Error al obtener los datos del usuario");

        const usuario = await response.json();

        // Llenar el formulario con los datos del usuario
        document.getElementById('nombre').value = usuario.nombre;
        document.getElementById('apellido_paterno').value = usuario.apellido_pat;
        document.getElementById('apellido_materno').value = usuario.apellido_mat;
        document.getElementById('email').value = usuario.correo;
        document.getElementById('password').value = usuario.contraseña; // No mostramos la contraseña
        document.getElementById('rol').value = usuario.rol_id_rol;

        // Guardar el ID del usuario en un atributo oculto
        document.getElementById('modalAgregarUsuario').setAttribute('data-id', idUsuario);
        // Ocultar "Guardar" y mostrar "Actualizar"
        document.getElementById('btnGuardarUsuario').style.display = "none";
        document.getElementById('btnActualizarUsuario').style.display = "block";


        // Abrir el modal
        $('#modalAgregarUsuario').modal('show');

    } catch (error) {
        console.error("Error al cargar usuario:", error);
        Swal.fire("Error", "No se pudieron cargar los datos del usuario.", "error");
    }
}

$('#modalAgregarUsuario').on('show.bs.modal', function () {
    const idUsuario = document.getElementById('modalAgregarUsuario').getAttribute('data-id');

    if (!idUsuario) {
        // Es un nuevo usuario
        document.getElementById('btnGuardarUsuario').style.display = "block";
        document.getElementById('btnActualizarUsuario').style.display = "none";
        document.getElementById('formAgregarUsuario').reset(); // Limpiar el formulario solo si es nuevo
    } else {
        // Es una edición
        document.getElementById('btnGuardarUsuario').style.display = "none";
        document.getElementById('btnActualizarUsuario').style.display = "block";
    }

    cargarRoles(); // Recargar los roles en el combo box
});

// Al cerrar el modal, limpiar datos
$('#modalAgregarUsuario').on('hidden.bs.modal', function () {
    document.getElementById('modalAgregarUsuario').removeAttribute('data-id'); // Eliminar ID
    document.getElementById('formAgregarUsuario').reset(); // Limpiar formulario
    document.getElementById('btnGuardarUsuario').style.display = "block"; // Mostrar botón Guardar por defecto
    document.getElementById('btnActualizarUsuario').style.display = "none"; // Ocultar botón Actualizar por defecto
});


document.addEventListener("DOMContentLoaded", function () {
    cargarPagina();
    document.getElementById('btnGuardarUsuario').addEventListener('click', agregarUsuario);
    document.getElementById('btnActualizarUsuario').addEventListener('click', actualizarUsuario);

    // Delegación de eventos para los botones de la tabla
    document.querySelector("#usuariosTable tbody").addEventListener("click", function (event) {
        if (event.target.closest(".delete-btn")) {
            const idUsuario = event.target.closest(".delete-btn").getAttribute("data-id");

            if (idUsuario == idUsuarioSesion) {
                Swal.fire("Error", "No puedes eliminar tu propia cuenta mientras tienes sesión activa.", "error");
                return;
            }

            eliminarUsuario(idUsuario);
        }

        if (event.target.closest(".edit-btn")) {
            const idUsuario = event.target.closest(".edit-btn").getAttribute("data-id");
            cargarDatosUsuario(idUsuario);
        }
    });
});

