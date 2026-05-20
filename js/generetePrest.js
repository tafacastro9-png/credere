$(document).ready(function () {

    /* =====================================================
       FUNCIÓN MONEDA COLOMBIANA
    ===================================================== */
    function formatoCOP(valor) {
        return Math.round(valor).toLocaleString('es-CO');
    }

    /* =====================================================
       INICIALIZAR SELECT2
    ===================================================== */
    function initSelect(selector, tipoReferencia = null) {
        $(selector).select2({
            width: '100%',
            placeholder: 'Buscar por nombre o folio',
            minimumInputLength: 2,
            ajax: {
                url: '../includes/consultaPersona.php',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    let query = {
                        term: params.term,
                        tipo: (selector === '#cliente_busqueda') ? 'cliente' : 'aval'
                    };

                    if (tipoReferencia !== null) {
                        query.tipoReferencia = tipoReferencia;
                    }

                    return query;
                },
                processResults: data => ({ results: data }),
                cache: true
            }
        });
    }

    initSelect('#cliente_busqueda');
    initSelect('#aval_busqueda', 0);
    initSelect('#aval_busquedafamiliar', 1);

    /* =====================================================
       INFO TIPO PRÉSTAMO
    ===================================================== */
    $('#id_tipo_prestamo').on('change', function () {

        const selected = $(this).find('option:selected');

        if (!selected.val()) {
            $('#info_prestamo').addClass('d-none');
            return;
        }

        const plazoDias = parseInt(selected.data('plazo')) || 0;
        const plazoMeses = Math.round(plazoDias / 30);
        const periodoGracia = parseInt(selected.data('periodo_gracia')) || 0;
		const factor = parseFloat(selected.data('factor'));
		console.log("Factor desde BD:", factor);

        $('#info_desc').text(selected.data('desc'));
        $('#info_tasa').text(selected.data('tasa'));
        $('#plazo_meses').text(plazoMeses).data('meses', plazoMeses);
        $('#periodo_gracia_valor').text(periodoGracia);
        $('#info_frec').text(selected.data('frec'));
        $('#info_multa').text(selected.data('multa'));
        $('#info_maximo').text(selected.data('max'));
		$('#info_factor').text(factor);
        $('#info_prestamo').removeClass('d-none');
    });

    /* =====================================================
       VALIDAR MONTO MÁXIMO
    ===================================================== */
    $('#monto_prestado').on('input', function () {

        const monto = parseFloat(this.value);
        const maximo = parseFloat($('#info_maximo').text());

        if (!isNaN(monto) && !isNaN(maximo) && monto > maximo) {
            Swal.fire(
                'Monto excedido',
                `El monto máximo permitido es $${maximo.toFixed(2)}`,
                'warning'
            );
            this.value = '';
        }
    });




});
