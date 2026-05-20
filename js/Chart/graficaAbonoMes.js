$(document).ready(function () {
    // Llamar a la función mostrarResultados con el año actual seleccionado inicialmente
    var yearActual = getYearActual();
    mostrarResultados(yearActual);

    // Vincular el evento onChange del select para mostrar resultados cuando se seleccione un año diferente
    $("#selectYear").on("change", function () {
        var selectedYear = $(this).val();
        mostrarResultados(selectedYear);
    });
});

// Función para obtener el año actual
function getYearActual() {
    var today = new Date();
    return today.getFullYear();
}

// Función para mostrar los resultados de la gráfica para un año específico
function mostrarResultados(year) {
    $.ajax({
        type: "POST",
        url: "../includes/consultAbonoMesChart.php",
        data: {
            year: year
        },
        dataType: "json",
        success: function (data) {
            // Actualizar los datos de la gráfica con los datos recibidos del servidor
            graficaBarras.data.datasets[0].data = data.data;
            graficaBarras.update();

            if (!data.has_ganancias) {
                // Mostrar SweetAlert2 si no hay ganancias registradas para el año seleccionado
                Swal.fire({
                    title: "Sin resultados",
                    text: "No hay ingresos registradas para el año seleccionado.",
                    icon: "info"
                });
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}
// Llamar a la función mostrarResultados con el año actual seleccionado inicialmente
$(document).ready(function () {
    var yearActual = getYearActual();
    $("#selectYear").val(yearActual); // Seleccionar el año actual en el select
    mostrarResultados(yearActual); // Mostrar la gráfica del año actual
    mostrarTotalGanancias(yearActual); // Mostrar el total de ganancias del año actual
});

// Datos para la gráfica de barras (esto podría provenir de una base de datos o una API)
var datos = {
    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    datasets: [{
        label: 'Ingresos Mensuales',
        data: [],
        backgroundColor: [
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(50, 205, 50, 0.6)',
            'rgba(139, 0, 139, 0.6)',
            'rgba(0, 0, 139, 0.6)',
            'rgba(255, 140, 0, 0.6)',
            'rgba(255, 69, 0, 0.6)',
            'rgba(128, 128, 0, 0.6)'
        ],
        borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(50, 205, 50, 1)',
            'rgba(139, 0, 139, 1)',
            'rgba(0, 0, 139, 1)',
            'rgba(255, 140, 0, 1)',
            'rgba(255, 69, 0, 1)',
            'rgba(128, 128, 0, 1)'
        ],
        borderWidth: 1
    }]
};

// Opciones de configuración de la gráfica
var opciones = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Crear la gráfica de barras
var ctx = document.getElementById('graficaBarras').getContext('2d');
var graficaBarras = new Chart(ctx, {
    type: 'bar',
    data: datos,
    options: opciones
});