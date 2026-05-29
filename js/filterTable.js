$(document).ready(function () {

    console.log("JS cargado");

    var table = $('#datatable').DataTable();

    $('#filtroEstado').on('change', function () {

        let valor = $(this).val();

        console.log("Filtro cambiado:", valor);

        if (valor === '') {
            table.column(0).search('').draw();
        } else {
            table.column(0).search(valor).draw();
        }

    });

});