 function generarReporte() {
        var star = document.getElementById("star").value;
        var fin = document.getElementById("fin").value;

        if (star === "" || fin === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Fechas Requeridas',
                text: 'Por favor, selecciona un rango de fechas antes de generar el reporte.',
            });
            return;
        }

        var url = '../includes/reportes/reporte_pagados_pdf.php?star=' + encodeURIComponent(star) + '&fin=' + encodeURIComponent(fin);

        window.open(url, '_blank', 'width=900,height=600,left=100,top=100');
    }

    $(document).ready(function() {
        $('#filtro').click(function(e) {
            e.preventDefault();
            var startDate = $('#star').val();
            var endDate = $('#fin').val();

            if (!startDate || !endDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fechas Requeridas',
                    text: 'Por favor, selecciona un rango de fechas.',
                });
                return;
            }

            $.ajax({
                url: '../includes/filtroPrestPay.php',
                method: 'POST',
                data: {
                    star: startDate,
                    fin: endDate
                },
                success: function(response) {
                    if ($.trim(response) === '') {
                        $('#datatable tbody').html('<tr><td colspan="9" class="text-center">No hay registros</td></tr>');
                    } else {
                        $('#datatable tbody').html(response);
                    }
                }
            });
        });
    });