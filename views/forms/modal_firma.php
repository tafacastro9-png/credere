<!-- MODAL FIRMA -->
<div class="modal fade" id="modalFirma" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Firma del Cliente</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <input type="hidden" id="idPrestamoFirma">

        <canvas id="signature-pad"
                width="600"
                height="250"
                style="border:2px solid #000; border-radius:10px;">
        </canvas>

        <br><br>

        <button type="button" class="btn btn-secondary" id="limpiarFirma">
          Limpiar
        </button>

        <button type="button" class="btn btn-success" id="guardarFirma">
          Guardar Firma
        </button>

      </div>

    </div>
  </div>
</div>