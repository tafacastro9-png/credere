    function actualizarContador() {
        $.ajax({
            url: '../includes/contar_cuotas.php',
            success: function(data) {
                $('#contador').html(data);
            }
        });
    }

    $(document).ready(function() {
        // Actualizamos el contador cada 5 segundos
        setInterval(actualizarContador, 5000);
    });