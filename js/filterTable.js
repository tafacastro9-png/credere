$(document).ready(function () {

    var table = $('#datatable').DataTable();

    $('#filtroEstado').on('change', function () {

        let valor = $(this).val();

        if (valor === '') {
            table.column(0).search('').draw();
        } else {
            table.column(0).search(valor).draw();
        }

    });

});