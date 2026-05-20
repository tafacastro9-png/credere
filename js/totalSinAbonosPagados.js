$(document).ready(function () {
    const $selectYear = $("#selectYear");

    // Función para obtener el año actual
    function getYearActual() {
        const today = new Date();
        return today.getFullYear();
    }

    // Función para mostrar el total de ganancias del año seleccionado
    function mostrarTotalGanancias(year) {
        $.ajax({
            type: "POST",
            url: "../includes/consultTotalSinAbonoMes.php",
            data: {
                year: year
            },
            dataType: "json"
        }).done(function (response) {
            const totalGanancias = parseFloat(response.total_ganancias);
            if (!isNaN(totalGanancias)) {
                const formattedGanancias = totalGanancias.toLocaleString("es-CO", {
                    style: "currency",
                    currency: "COP",
                    minimumFractionDigits: 0
                });
                $("#totalGanancias").text(`Total de ingresos del año: ${formattedGanancias}`);
            } else {
                $("#totalGanancias").text("Total de ingresos del año: Valor no disponible");
            }
        }).fail(function (xhr, status, error) {
            console.error(xhr.responseText); // Mostrar el error en la consola
        });
    }

    // Llamar a la función mostrarTotalGanancias con el año actual seleccionado inicialmente
    const yearActual = getYearActual();
    $selectYear.val(yearActual); // Seleccionar el año actual en el select
    mostrarResultados(yearActual); // Mostrar la gráfica del año actual
    mostrarTotalGanancias(yearActual); // Mostrar el total de ganancias del año actual

    // Agregar el evento change al select para actualizar las ganancias cuando se seleccione un año diferente
    $selectYear.on("change", function () {
        const selectedYear = $(this).val();
        mostrarTotalGanancias(selectedYear);
    });
});