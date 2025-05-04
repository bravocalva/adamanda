let reservasTable;

$(document).ready(async function() {
    try {
        // Obtener datos de la API
        const response = await fetch('http://localhost/adamanda/api_rest_hotel/reservas');
        if (!response.ok) throw new Error('Error al obtener datos');
        const reservas = await response.json();
        
        // Inicializar DataTable con configuración básica
        reservasTable = $('#ReservasTable').DataTable({
            data: reservas,
            columns: [
                { data: 'id_reservacion', className: 'text-center' },
                { data: 'fecha_entrada', className: 'text-center' },
                { data: 'fecha_salida', className: 'text-center' },
                { data: 'numero_habitacion', className: 'text-center' },
                { 
                    data: 'total', 
                    className: 'text-center',
                    render: function(data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : '-';
                    }
                },
                { 
                    data: 'adelanto', 
                    className: 'text-center',
                    render: function(data) {
                        return '$' + parseFloat(data).toFixed(2);
                    }
                },
                { 
                    data: 'total_restante', 
                    className: 'text-center',
                    render: function(data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : '-';
                    }
                },
                { data: 'nombre_cliente' },
                { 
                    data: 'status_reservacion',
                    render: function(data) {
                        let badgeClass = "";
                        switch (data) {
                            case "ACTIVO":
                                badgeClass = "badge-success";
                                break;
                            case "RESERVADO":
                                badgeClass = "badge-warning";
                                break;
                            case "TERMINADO":
                                badgeClass = "badge-secondary";
                                break;
                            case "CANCELADO":
                                badgeClass = "badge-danger";
                                break;
                            default:
                                badgeClass = "badge-secondary";
                        }
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    },
                    className: 'text-center'
                },
                { 
                    data: null,
                    render: function(data, type, row) {
                        // Determinar si el botón debe estar deshabilitado
                        const isDisabled = !(row.status_reservacion === "RESERVADO" || row.status_reservacion === "ACTIVO");
                        return `
                            <button class="btn btn-sm btn-danger btn-cancelar" 
                                    data-id="${row.id_reservacion}"
                                    ${isDisabled ? 'disabled' : ''}>
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    },
                    className: 'text-center',
                    orderable: false
                }
            ],
            language: {
                url: '../assets/plugins/datatables/i18n/Spanish.json',
                LengthMenu: '' 
            },
            responsive: true,
            order: [[0, 'desc']] // Ordenar por ID descendente
        });

        // Evento para el botón de cancelar (solo si no está deshabilitado)
        $('#ReservasTable').on('click', '.btn-cancelar:not(:disabled)', function() {
            const idReservacion = $(this).data('id');
            cancelarReservacion(idReservacion);
        });
        
    } catch (error) {
        console.error('Error:', error);
        $('#ReservasTable').before(
            '<div class="alert alert-danger">Error al cargar las reservaciones. Intente nuevamente.</div>'
        );
    }
});

// Función para cancelar reservación
async function cancelarReservacion(id) {
    if (confirm('¿Estás seguro de cancelar esta reservación?')) {
        try {
            const response = await fetch(`http://localhost/adamanda/api_rest_hotel/cancelar/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response !== 'true') throw new Error('Error al cancelar la reservación');

            const result = await response.json();
            alert(result.message || 'Reservación cancelada exitosamente');
            // Recargar la tabla 
            reservasTable.ajax.reload(null, false);
            
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cancelar la reservación: ' + error.message);
        }
    }
}