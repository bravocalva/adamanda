let dataTable;
async function obtenerDatos() {
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/habitaciones_info');
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

function actualizarDataTable(datos) {
    if (!dataTable) {
        dataTable = $('#HabitacionesTable').DataTable({
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
            "data": datos, // Asignar datos directamente
            "columns": [{
                "data": "id_habitacion"
            },
            {
                "data": "numero"
            },
            {
                "data": "descripcion"
            },
            {
                "data": "tipo_habitacion"
            },
            {
                "data": "estado",
                "render": function (data) {
                    let badgeClass = "";
                    switch (data) {
                        case "DISPONIBLE":
                            badgeClass = "badge-success"; // Verde
                            break;
                        case "OCUPADO":
                            badgeClass = "badge-danger"; // Rojo
                            break;
                        case "RESERVADO":
                            badgeClass = "badge-warning"; // Amarillo
                            break;
                        case "LIMPIEZA":
                            badgeClass = "badge-info"; // Azul
                            break;
                        default:
                            badgeClass = "badge-secondary"; // Gris
                    }
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                "data": "ruta_imagen",
                "render": function (data) {
                    return data ?
                        `<img src="${data}" loading="lazy" style="width: 90px; height: 90px; object-fit: cover;" alt="Imagen habitación">` :
                        '<img src="/ruta/a/imagen/placeholder.jpg" style="width: 50px; height: 50px;" alt="Sin imagen">';
                }
            },
            {
                "data": "id_habitacion",
                "render": function (data, type, row) {
                    return `
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${data}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm delete-btn" onclick="eliminar(${data}, '${row.ruta_imagen}')"> <i class="fas fa-trash"></i></button>
                            `;
                }
            }
            ]
        });
    } else {
        dataTable.clear().rows.add(datos).draw();
    }
}

async function cargarPagina() {
    document.getElementById('loading').style.display = 'block';
    await actualizarTabla();
    document.getElementById('loading').style.display = 'none';
}

async function actualizarTabla() {
    const datos = await obtenerDatos();

    if (datos.length === 0) {
        console.warn('No hay datos para mostrar en la tabla.');
        return;
    }
    actualizarDataTable(datos);
    document.getElementById('HabitacionesTable').style.display = 'table';
}

async function cargarTipos() {
    const selectRol = document.getElementById('tipoHabitacion');
    const actRol = document.getElementById('tipoHabitacion_Act');
    try {
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/tipohabitacion');
        const response2 = await fetch('http://localhost/adamanda/api_rest_hotel/tipohabitacion');
        if (!response.ok) throw new Error('Error al obtener tipos');
        if (!response2.ok) throw new Error('Error al obtener tipos');

        const tipoHab = await response.json(); // Convertir respuesta a JSON
        const tipoHab2 = await response2.json();
        // Limpiar opciones previas
        selectRol.innerHTML = '<option value="0">Seleccione un tipo</option>';
        actRol.innerHTML = '<option value="0">Seleccione un tipo</option>';

        // Recorrer los datos y agregarlos al combo box
        tipoHab.forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo.id_tipo_hab; // ID del rol
            option.textContent = tipo.nombre; // Nombre del rol
            selectRol.appendChild(option);
        });

        // Recorrer los datos y agregarlos al combo box
        tipoHab2.forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo.id_tipo_hab; // ID del rol
            option.textContent = tipo.nombre; // Nombre del rol
            actRol.appendChild(option);
        });

    } catch (error) {
        console.error('Error al cargar roles:', error);
        selectRol.innerHTML = '<option value="">Error al cargar roles</option>';
    }
}

async function agregarHabitacion() {
    const form = document.getElementById("formHabitacion");

    const formData = new FormData(form);
    console.log([...formData]); // Verificar que el archivo se está enviando

    const numero = document.getElementById("numeroHab").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const tipo_habitacion_id = document.getElementById("tipoHabitacion").value;
    const estado_id = '1'; //Estado en disponible por defecto al subir una nueva habitacion

    if (!numero || !descripcion) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    if (tipo_habitacion_id === '0') {
        Swal.fire('Error', 'Selecciona un tipo de habitacion', 'error');
        return;
    }


    // Verificar si el archivo fue seleccionado, si no, agregar la imagen por defecto
    const imagenInput = document.querySelector('input[type="file"][name="image"]'); //
    if (!imagenInput.files.length) {
        const defaultImage = '../assets/dist/img/default.jpg'; // Ruta de la imagen por defecto
        const defaultImageFile = await fetch(defaultImage).then(res => res.blob());
        formData.append('image', defaultImageFile, 'imagen_default.jpg');
    }

    try {
        // Subir imagen primero
        const uploadResponse = await fetch("habitaciones.php", {
            method: "POST",
            body: formData
        });

        const uploadResult = await uploadResponse.json();
        console.log(uploadResult); // Verificar respuesta en consola

        if (!uploadResult.success) {
            throw new Error(uploadResult.error || "Error al subir la imagen");
        }

        const rutaImagen = uploadResult.ruta; // Guardamos la ruta
        console.log("Imagen subida con éxito:", rutaImagen);

        const habitacionData = {
            numero,
            descripcion,
            tipo_habitacion_id,
            estado_id,
            ruta_imagen: rutaImagen // Ruta de la imagen obtenida
        };

        console.log("Enviando a API:", habitacionData); // Ver en consola antes de enviarlo

        const apiResponse = await fetch("http://localhost/adamanda/api_rest_hotel/habitaciones", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(habitacionData)
        });

        const apiResult = await apiResponse.json();
        console.log(apiResult);

        if (apiResult.ban === 0) {

            Swal.fire("Éxito", "Habitación agregada correctamente", "success");
            $('#modalAgregarHabitacion').modal('hide');
            actualizarTabla(); // Refrescar la tabla
        }

        if (apiResult.ban === 1) {
            console.log("Procediendo a eliminar: ", rutaImagen);

            // Crear un nuevo FormData para enviar la ruta
            const formData = new FormData();
            formData.append('accion', 'eliminar_imagen');
            formData.append('ruta', rutaImagen); // Enviar la ruta como FormData

            // Enviar la solicitud con FormData
            await fetch("habitaciones.php", {
                method: "POST",
                body: formData
            });

            Swal.fire("Error", "Número de habitación ya existente", "error");
        }


    } catch (error) {
        console.error("Error al subir la imagen:", error);

        Swal.fire({
            title: "Error",
            text: error.message,
            icon: "error",
        });
    }
}




async function eliminar(id, rutaImagen) {
    if (!rutaImagen) {
        Swal.fire("Error", "No se encontró la imagen a eliminar.", "error");
        return;
    }

    const confirmacion = await Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const imgResponse = await fetch("habitaciones.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ "ruta": rutaImagen })
        });

        const imgResult = await imgResponse.json();
        if (!imgResult.success) {
            Swal.fire("Error", "No se pudo eliminar la imagen.", "error");
            return;
        }

        // Eliminar habitación de la API
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/habitaciones/${id}`, {
            method: "DELETE"
        });

        const data = await response.json();
        if (data.ban === 0) {
            Swal.fire("Eliminado", "La habitación ha sido eliminada.", "success");
            actualizarTabla();
        } else {
            Swal.fire("Error", "No se encontró la habitación a eliminar", "error");
        }

    } catch (error) {
        console.error("Error al eliminar la habitación:", error);
        Swal.fire("Error", "Ocurrió un problema al eliminar la habitación.", "error");
    }
}

async function cargarDatos(id) {
    try {
        const response = await fetch(`http://localhost/adamanda/api_rest_hotel/habitaciones/${id}`);
        if (!response.ok) throw new Error("Error al obtener los datos el articulo");
        const hab = await response.json();
        console.log(hab);
        // Llenar el formulario con los datos
        document.getElementById('numeroHab_Act').value = hab.numero;
        document.getElementById('descripcion_Act').value = hab.descripcion;
        document.getElementById('tipoHabitacion_Act').value = hab.tipo_habitacion_id_tipo_hab;
        document.getElementById('rutaImagenActual').value = hab.ruta_imagen;

        // Guardar el ID del articulo en un atributo oculto
        document.getElementById('modalActualizarHabitacion').setAttribute('data-id', id);
        $('#modalActualizarHabitacion').modal('show');
    }

    catch (error) {
        console.error("Error al cargar habitacion:", error);
        Swal.fire("Error", "No se pudieron cargar los datos de la habitacion.", "error");
    }
}

async function actualizarHabitacion() {
    const form = document.getElementById("formActualizarHabitacion");
    const formData = new FormData(form);
    console.log([...formData]); // Verificar que el archivo se está enviando correctamente

    const id_hab = document.getElementById('modalActualizarHabitacion').getAttribute("data-id");
    const numero = document.getElementById("numeroHab_Act").value.trim();
    const descripcion = document.getElementById("descripcion_Act").value.trim();
    const tipo_habitacion_id = document.getElementById("tipoHabitacion_Act").value;
    const estado_id = '1'; // Estado en disponible por defecto
    const rutaImagenActual = document.getElementById('rutaImagenActual').value; // Ruta de la imagen actual

    // Verificar si los campos están completos
    if (!numero || !descripcion) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    if (tipo_habitacion_id === '0') {
        Swal.fire('Error', 'Selecciona un tipo de habitación', 'error');
        return;
    }

    try {
        let nuevaRutaImagen = rutaImagenActual; // Mantener la imagen actual
        const imageInput = document.getElementById('imageInput_Act');

        if (imageInput.files && imageInput.files[0]) { //verificar si se selecciona una imagen desde imageInputAct
            // Si se seleccionó una nueva imagen, manejar la subida de la imagen
            const imageFormData = new FormData();
            imageFormData.append('image', imageInput.files[0]);
            imageFormData.append('rutaImagenActual', rutaImagenActual); // Ruta de la imagen actual para eliminarla

            console.log("Subiendo nueva imagen...");
            // Subir la nueva imagen
            const uploadResponse = await fetch("habitaciones.php", {
                method: "POST",
                body: imageFormData
            });

            const uploadResult = await uploadResponse.json();
            console.log("Resultado de la subida de imagen:", uploadResult); // Verificar respuesta

            if (!uploadResult.success) {
                throw new Error(uploadResult.error || "Error al subir la imagen");
            }

            nuevaRutaImagen = uploadResult.ruta; // Asignar la nueva ruta
            console.log("Imagen subida con éxito:", nuevaRutaImagen);
        }


        // Enviar los datos de la habitación a la API
        const habitacionData = {
            id: id_hab,
            numero,
            descripcion,
            tipo_habitacion_id,
            estado_id,
            ruta_imagen: nuevaRutaImagen // Ruta de la imagen obtenida o la actual
        };

        console.log("Datos a enviar a la API:", habitacionData); // Ver en consola antes de enviarlo

        const apiResponse = await fetch(`http://localhost/adamanda/api_rest_hotel/habitaciones`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(habitacionData)
        });

        const apiResult = await apiResponse.json();
        console.log("Resultado de la API:", apiResult); // Verificar respuesta de la API

        if (apiResult.ban === 0) {
            Swal.fire("Éxito", "Habitación actualizada correctamente", "success");
            $('#modalActualizarHabitacion').modal('hide');
            actualizarTabla(); // Refrescar la tabla
        }

        if (apiResult.ban === 1) {
            Swal.fire("Error", "No se pudo actualizar la habitación", "error");
        }

    } catch (error) {
        console.error("Error al actualizar la habitación:", error);

        Swal.fire({
            title: "Error",
            text: error.message,
            icon: "error",
        });
    }
}


document.addEventListener('DOMContentLoaded', function () {

    cargarPagina();
    cargarTipos(); // Cargar tipos de habitacion en formulario
    document.getElementById('btnGuardarHabitacion').addEventListener('click', agregarHabitacion);
    document.getElementById('btnActualizarHabitacion').addEventListener('click', actualizarHabitacion);

    document.querySelector("#HabitacionesTable tbody").addEventListener("click", function (event) {
        if (event.target.closest(".edit-btn")) {
            const id_habitacion = event.target.closest(".edit-btn").getAttribute("data-id");
            cargarDatos(id_habitacion);
        }
    });

});

$(document).ready(function () {
    // Cargar la imagen de preview al seleccionar
    $('#imageInput').change(function (e) {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (event) {
                $('#imagePreview').attr('src', event.target.result);

                // Actualizar el label del custom file input
                var fileName = e.target.files[0].name;
                $(e.target).next('.custom-file-label').html(fileName);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    $('#modalActualizarHabitacion').on('shown.bs.modal', function () {
        var rutaImagen = $('#rutaImagenActual').val(); // Obtener el valor del campo de texto
        // Si hay una ruta de imagen, actualizamos la vista previa
        if (rutaImagen) {
            $('#imagePreview_Act').attr('src', rutaImagen); // Asigna la ruta de la imagen al src
        } else {
            console.log("Error al cargar imagen");
        }

    });

    $('#imageInput_Act').change(function (e) {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (event) {
                $('#imagePreview_Act').attr('src', event.target.result);

                // Actualizar el label del custom file input
                var fileName = e.target.files[0].name;
                $(e.target).next('.custom-file-label').html(fileName);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    $('#modalActualizarHabitacion').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#imagePreview_Act').attr('src', '../assets/dist/img/default.jpg'); // Cambia por la imagen por defecto
        // Restablecer el nombre del archivo en el input de archivo
        $('#imageInput_Act').next('.custom-file-label').html('Selecciona un archivo');
    });


    $('#modalAgregarHabitacion').on('hidden.bs.modal', function () {
        // Reiniciar todos los campos del formulario
        $(this).find('form')[0].reset(); // Reinicia los campos del formulario
        // Restablecer la imagen a la imagen predeterminada
        $('#imagePreview').attr('src', '../assets/dist/img/default.jpg'); // Cambia por la imagen por defecto
        // Restablecer el nombre del archivo en el input de archivo
        $('#imageInput').next('.custom-file-label').html('Selecciona un archivo');
    });

});

