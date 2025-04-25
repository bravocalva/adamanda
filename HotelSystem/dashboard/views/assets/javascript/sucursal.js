//Llenar datos por medio de la api
document.addEventListener("DOMContentLoaded", () => {
    fetch("../../../../api_rest_hotel/sucursal")
        .then(res => res.json())
        .then(data => {
            document.getElementById("logoSucursal").src = data.ruta;
            document.getElementById("nombreSucursal").textContent = data.nombre;
            document.getElementById("direccionSucursal").textContent = data.direccion;
            document.getElementById("correoSucursal").textContent = data.correo;
            document.getElementById("telefonoSucursal").textContent = data.telefono;
            document.getElementById("ciudadSucursal").textContent = data.ciudad;
            document.getElementById("fechaActualizacion").textContent = data.fecha_act;
            document.getElementById("rfcSucursal").textContent = data.rfc;

            // Guardar los datos en una variable global
            window.sucursalData = data;
        });

    // Cuando se haga clic en "Editar", llenar el formulario dentro del modal
    document.getElementById("editarBtn").addEventListener("click", () => {
        const data = window.sucursalData;

        document.getElementById("nombreInput").value = data.nombre;
        document.getElementById("direccionInput").value = data.direccion;
        document.getElementById("correoInput").value = data.correo;
        document.getElementById("telefonoInput").value = data.telefono;
        document.getElementById("ciudadInput").value = data.ciudad;
        document.getElementById("rfcInput").value = data.rfc;
        // Mostrar la imagen actual y guardar su ruta en un campo oculto
        document.getElementById("rutaLogoActual").value = data.ruta;
        document.getElementById("logoPreview").src = data.ruta;


    });

});

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formEditar");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Subir la imagen al servidor (para obtener su ruta)
        const formData = new FormData(form);
        let rutaImagen = "";

        try {
            // Subir imagen primero
            const uploadResponse = await fetch("sucursal.php", {
                method: "POST",
                body: formData
            });
            const uploadResult = await uploadResponse.json();

            if (!uploadResult.success) {
                throw new Error(uploadResult.error || "Error al subir la imagen");
            }

            rutaImagen = uploadResult.ruta; // Guardamos la ruta
            console.log(rutaImagen);

            //Preparar datos para la API (incluyendo la ruta de la imagen)
            const datosParaApi = {
                nombre: form.nombre.value, //usa en este caso el valor de name de cada input
                direccion: form.direccion.value,
                ciudad: form.ciudad.value,
                telefono: form.telefono.value,
                correo: form.correo.value,
                rfc: form.rfc.value,
                ruta: rutaImagen
            };

            console.log(datosParaApi);

            const apiResponse = await fetch("../../../../api_rest_hotel/sucursal", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(datosParaApi)
            });

            if (!apiResponse.ok) {
                const errorData = await apiResponse.json();
                throw new Error(errorData.message || "Error en la API");
            }

            $('#modalEditar').modal('hide'); // Cerrar modal
            
            Swal.fire({
                icon: 'success',
                title: '¡Datos guardados correctamente!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            // Actualizar la información en la página sin recargar
            document.getElementById("nombreSucursal").textContent = datosParaApi.nombre;
            document.getElementById("direccionSucursal").textContent = datosParaApi.direccion;
            document.getElementById("ciudadSucursal").textContent = datosParaApi.ciudad;
            document.getElementById("telefonoSucursal").textContent = datosParaApi.telefono;
            document.getElementById("correoSucursal").textContent = datosParaApi.correo;
            document.getElementById("rfcSucursal").textContent = datosParaApi.rfc;


            // Si hay una nueva imagen, actualizarla en la vista
            if (rutaImagen) {
                document.getElementById("logoSucursal").src = rutaImagen;
                document.getElementById("logoPreview").src=rutaImagen;
            }


        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            console.error("Error detallado:", error);
        }
    });
});


// CARGAR LA IMAGEN SELECCIONADA EN EL MODAL
$(document).ready(function () {
    $('#logoInput').change(function (e) {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (event) {
                $('#logoPreview').attr('src', event.target.result);
                // Actualizar el label del custom file input
                var fileName = e.target.files[0].name;
                $(e.target).next('.custom-file-label').html(fileName);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });
});
