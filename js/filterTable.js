$(document).ready(function () {

    console.log("JS cargado");

    var table = $('#datatable').DataTable({

        pageLength: 10,

        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
        },

        dom: 'Bfrtip',

        buttons: [
            {
                extend: 'excelHtml5',
                text: '📊 Exportar Excel',
                className: 'btn btn-success'
            }
        ]

    });

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

        fechaRegistro = fechaRegistro.substring(0,10);

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