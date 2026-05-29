$(document).ready(function () {

    console.log("JS cargado");

    // Obtener DataTable existente
    var table = $('#datatable').DataTable();

    // ==========================
    // BOTÓN EXPORTAR EXCEL
    // ==========================

    new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'excelHtml5',
                text: '📊 Exportar Excel',
                className: 'btn btn-success'
            }
        ]
    });

    table.buttons().container()
        .appendTo('#datatable_wrapper .col-md-6:eq(0)');

    // ==========================
    // FILTRO ESTADO
    // ==========================

    $('#filtroEstado').on('change', function () {

        let valor = $(this).val();

        if (valor === '') {
            table.column(0).search('').draw();
        } else {
            table.column(0).search(valor).draw();
        }

    });

    // ==========================
    // FILTRO FECHAS
    // ==========================

    $.fn.dataTable.ext.search.push(function(settings, data) {

        let fechaDesde = $('#fechaDesde').val();
        let fechaHasta = $('#fechaHasta').val();

        let fechaRegistro = data[8];

        if (!fechaRegistro) {
            return true;
        }

        fechaRegistro = fechaRegistro.substring(0, 10);

        if (fechaDesde && fechaRegistro < fechaDesde) {
            return false;
        }

        if (fechaHasta && fechaRegistro > fechaHasta) {
            return false;
        }

        return true;

    });

    $('#fechaDesde, #fechaHasta').on('change', function () {

        table.draw();

    });

});