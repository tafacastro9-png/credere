<?php include "../includes/header.php"; ?>
<!-- ========== table components start ========== -->

<style>
    .card-style ul {
        list-style: none;
        padding-left: 0;
    }

    .card-style ul li::before {
        content: "✔ ";
        color: green;
        margin-right: 5px;
    }

    .note {
        background-color: #fff7cc;
        border-left: 5px solid #ffcc00;
        padding: 10px;
        margin: 10px 0;
        border-radius: 6px;
        font-style: italic;
    }

    .textP {
        line-height: 1.6;
    }

    .card-style a {
        color: #007bff;
        text-decoration: none;
    }

    .card-style a:hover {
        text-decoration: underline;
    }

    .card-style h4 {
        margin-top: 20px;
        color: #333;
        font-weight: bold;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }

    .card-style p,
    .card-style ul {
        font-size: 16px;
    }
</style>

<section class="table-components">
    <div class="container-fluid">
        <br><br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30 p-4">
                    <h2 class="text-center mb-4">SOPORTE TÉCNICO</h2>

                

                    <hr>

                    <h4>¿Qué hace este sistema?</h4>
                    <ul>
                        <li>✔ Registro de clientes y referencias.</li>
                        <li>✔ Creación y autorización de préstamos.</li>
                        <li>✔ Generación de cronogramas y contratos.</li>
                        <li>✔ Impresión de tickets de pago.</li>
                        <li>✔ Gestión de usuarios y tipos de préstamos.</li>
                        <li>✔ Registro de abonos (cuotas) y actualización de estado.</li>
                        <li>✔ Generación de reportes y gráficas estadísticas.</li>
                        <li>✖ No realiza cobros con tarjeta. Los pagos se registran manualmente cuando el cliente paga en efectivo en oficinas.</li>
                    </ul>

                    <br>
                    <h4>Novedades de Actualización</h4>
                    <p class="note">Las actualizaciones no siempre implican mejoras; en ocasiones, consisten en correcciones o ajustes necesarios en módulos afectados del sistema.</p>

                    <ul>
                        <li>[✓] Optimización en generación de cronogramas.</li>
                        <li>[✓] Mejoras en la impresión de comprobantes de pago.</li>
                        <li>[✓] Correcciones menores en el módulo de préstamos.</li>
                        <li>[✓] Estilo más moderno en la pantalla de login.</li>
                    </ul>

                    <p class="note"><strong>Fecha de Actualización:</strong> 26/02/2026.</p>

                    <br>
                    <h4>Sobre el Desarrollador</h4>
                    <p><strong>Aviso:</strong> Este sistema </strong> ha sido desarrollado por el Ing. Luis Fabian Castro.</p>
                    <p>No está permitido vender o distribuir este sistema a terceros sin la autorización expresa de <strong>Luis Fabian Castro</strong>. Cualquier uso no autorizado puede estar sujeto a consecuencias legales. Por favor, respete el derecho de propiedad intelectual.</p>

                    <p>Desarrollado y mantenido por <a href="Luis Fabian Castro" target="_blank">Luis Fabian Castro</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include "../includes/footer.php"; ?>