let reservasTable;
let datosSucursal = {};

function verDetalles(id_factura, total) {
    console.log("Viendo detalles factura con ID:", id_factura);
    totalFacturaSeleccionada = total;
    $.get(`http://localhost/adamanda/api_rest_hotel/factura_detalle/${id_factura}`, function (data) {

        const reservacion = data[0][0]; // Primer array, primer objeto
        const articulos = data[2]; // Tercer array (artículos de la habitación)
        const movimientos = data[1]; // Segundo array (movimientos adicionales)

        console.log(reservacion);
        console.log(articulos);
        console.log(movimientos);

        // Mostrar detalles de la reservación
        $('#reservacionCliente').text(reservacion.cliente);
        $('#reservacionFechaEntrada').text(reservacion.fecha_entrada);
        $('#reservacionFechaSalida').text(reservacion.fecha_salida);
        $('#reservacionAdelanto').text(reservacion.adelanto);
        $('#reservacionTotal').text(reservacion.total_reservacion);
        $('#reservacionStatus').text(reservacion.status_reservacion);
        $('#reservacionUsuario').text(reservacion.usuario);

        // Mostrar los artículos
        if (articulos.length > 0) {
            let articulosHTML = '';
            articulos.forEach(articulo => {
                articulosHTML += `
                    <tr>
                        <td>${articulo.articulo}</td>
                        <td>${articulo.cantidad}</td>
                        <td>${articulo.precio}</td>
                        <td>${articulo.habitacion}</td>
                        <td>${articulo.fecha}</td>
                    </tr>
                `;
            });
            $('#articulosDetails tbody').html(articulosHTML);
        } else {
            $('#articulosDetails tbody').html('<tr><td colspan="5">No hay artículos registrados.</td></tr>');
        }

        // Mostrar los movimientos adicionales
        if (movimientos.length > 0) {
            let movimientosHTML = '';
            movimientos.forEach(movimiento => {
                movimientosHTML += `
                    <tr>
                        <td>${movimiento.descripcion}</td>
                        <td>${movimiento.monto}</td>
                    </tr>
                `;
            });
            $('#movimientosDetails tbody').html(movimientosHTML);
        } else {
            $('#movimientosDetails tbody').html('<tr><td colspan="2">No hay movimientos adicionales registrados.</td></tr>');
        }

        // Botones del modal-footer (COMPATIBLE CON BOOTSTRAP 4)
        $('#reservacionModal .modal-footer').html(`
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="generarTicketFactura(${id_factura})">
                        <i class="fas fa-print"></i> Imprimir Ticket
                    </button>
                `);

        $('#reservacionModal').modal('show');
    }).fail(function () {
        console.error('Error al cargar los datos.');
    });
}

function cargarSucursal(callback) {
    $.get('http://localhost/adamanda/api_rest_hotel/sucursal', function (data) {
        datosSucursal = data;
        if (callback) callback();
    }).fail(function () {
        console.error('Error al cargar datos de la sucursal');
    });
}

function generarTicketFactura(id_factura) {
    console.log("Generando ticket con factura NO. : ", id_factura);

    // Cargar los datos de la sucursal y la factura
    $.get('http://localhost/adamanda/api_rest_hotel/sucursal', function (sucursal) {
        $.get(`http://localhost/adamanda/api_rest_hotel/factura_detalle/${id_factura}`, function (data) {
            const reservacion = data[0][0];
            const movimientos = data[1] || [];
            const articulos = data[2] || [];

            // Generar el HTML del ticket
            let ticketHTML = `
                <div style="font-family: monospace; font-size: 13px; width: 300px; padding: 10px;">
                    ${sucursal.ruta ? `<img src="${sucursal.ruta}" style="width: 80px; display: block; margin: auto;">` : ''}
                    <h3 style="text-align: center; margin: 5px 0;">${sucursal.nombre}</h3>
                    <p style="text-align: center; margin: 0;">${sucursal.direccion}</p>
                    <p style="text-align: center; margin: 0;">${sucursal.ciudad}</p>
                    <p style="text-align: center; margin: 0;">Tel: ${sucursal.telefono}</p>
                    <p style="text-align: center; margin: 0;">Correo: ${sucursal.correo}</p>
                    <p style="text-align: center; margin: 0;">RFC: ${sucursal.rfc}</p>
                    <hr>

                    <h4 style="text-align: center;">Detalles de la Reservación</h4>
                    <p><strong>Cliente:</strong> ${reservacion.cliente}</p>
                    <p><strong>Fecha Entrada:</strong> ${reservacion.fecha_entrada}</p>
                    <p><strong>Fecha Salida:</strong> ${reservacion.fecha_salida}</p>
                    <p><strong>Usuario:</strong> ${reservacion.usuario}</p>
                    <p><strong>Total Reservación:</strong> ${reservacion.total_reservacion}</p>
                    <hr>

                    <h4>Artículos en Habitación</h4>
                    ${articulos.length > 0 ? articulos.map(a => ` 
                        <p>• ${a.articulo} (${a.precio}) - ${a.cantidad}</p>
                    `).join('') : '<p>No hay movimientos de artículos.</p>'}

                    <hr>
                    <h4>Cargos Adicionales</h4>
                    ${movimientos.length > 0 ? movimientos.map(m => ` 
                        <p>${m.descripcion}: $${m.monto}</p>
                    `).join('') : '<p>No hay cargos adicionales.</p>'}

                    <hr>
                    <p><strong>Adelanto:</strong> $${reservacion.adelanto}</p>
                    <p><strong>Total a Pagar:</strong> $${parseFloat(totalFacturaSeleccionada).toFixed(2)}</p>
                    <hr>
                    <p style="text-align: center;">¡Gracias por su visita!</p>
                </div>
            `;
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(ticketHTML);
            printWindow.document.close();
            printWindow.focus();
            const images = printWindow.document.images;
            let loaded = 0;

            if (images.length > 0) {
                for (let img of images) {
                    img.onload = img.onerror = () => {
                        loaded++;
                        if (loaded === images.length) {
                            printWindow.print();
                        }
                    };
                }
            } else {
                printWindow.print();
            }

        });
    });
}




$(document).ready(async function () {
    try {
        // Obtener datos de la API
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/facturas');
        if (!response.ok) throw new Error('Error al obtener datos');
        const facturas = await response.json();

        // Inicializar DataTable con configuración básica
        facturasTable = $('#FacturasTable').DataTable({
            data: facturas,
            columns: [
                { data: 'id_factura', className: 'text-center' },
                { data: 'id_reservacion', className: 'text-center' },
                { data: 'fecha_emision', className: 'text-center' },
                { data: 'tipo_pago', className: 'text-center' },
                {
                    data: 'total',
                    className: 'text-center',
                    render: function (data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : '-';
                    }
                },
                { data: 'nombre_usuario', className: 'text-center' },
                { data: 'nombre_cliente', className: 'text-center' },
                {
                    data: 'id_factura',
                    render: function (data, type, row) {
                        return `
                            <button onclick="verDetalles(${data}, ${row.total})" 
                                class="btn btn-success btn-sm edit-btn" 
                                data-id="${data}" 
                                data-total="${row.total}">
                                Ver Detalles
                            </button>
                        `;
                    }
                }
            ],
            language: {
                url: '../assets/plugins/datatables/i18n/Spanish.json',
                LengthMenu: ''
            },
            responsive: true,
            order: [[0, 'desc']]// Ordenar por ID descendente

        });

    } catch (error) {
        console.error('Error:', error);
        $('#FacturasTable').before(
            '<div class="alert alert-danger">Error al cargar las facturas. Intente nuevamente.</div>'
        );
    }
});