$(document).ready(function () {

    console.log("JS cargado");

    var table = $('#datatable').DataTable();

    // =====================================
    // FILTRO ESTADO
    // =====================================

    $('#filtroEstado').on('change', function () {

        let valor = $(this).val();

        if (valor === '') {
            table.column(0).search('').draw();
        } else {
            table.column(0).search(valor).draw();
        }

    });

    // =====================================
    // FILTRO FECHA
    // =====================================

    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {

            let fechaDesde = $('#fechaDesde').val();
            let fechaHasta = $('#fechaHasta').val();

            // Columna FechaRegistro
            let fechaTabla = data[8];

            if (!fechaTabla) return true;

            let fechaRegistro = fechaTabla.substring(0, 10);

            if (fechaDesde && fechaRegistro < fechaDesde) {
                return false;
            }

            if (fechaHasta && fechaRegistro > fechaHasta) {
                return false;
            }

            return true;
        }
    );

    // =====================================
    // RECARGAR TABLA AL CAMBIAR FECHAS
    // =====================================

    $('#fechaDesde, #fechaHasta').on('change', function () {
        table.draw();
    });

});