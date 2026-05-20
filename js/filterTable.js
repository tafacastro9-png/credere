$(document).ready(function () {

    console.log("Esperando DataTable existente...");

    let interval = setInterval(function () {

        if ($.fn.DataTable.isDataTable('#datatable')) {

            clearInterval(interval);

            console.log("DataTable detectada ✅");

            var table = $('#datatable').DataTable();

            $('#filtroEstado').on('change', function () {

                let valor = $(this).val();

                if (valor === "") {
                    table.column(0).search("").draw();
                } else {
                    table.column(0).search(valor).draw();
                }

            });

        }

    }, 200);

});