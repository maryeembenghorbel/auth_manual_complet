<div class="modal fade" id="deleteEquipmentModal" tabindex="-1" aria-labelledby="deleteEquipmentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteEquipmentLabel">
            <i class="fas fa-triangle-exclamation me-2"></i>Confirmation de suppression
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">Voulez-vous vraiment supprimer cet équipement :</p>
        <p class="fw-bold fs-5" id="deleteEquipmentName"></p>
        <p class="text-muted mb-0">Cette action est irréversible (soft delete).</p>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Annuler
        </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteEquipmentBtn">
            <i class="fas fa-trash-alt me-1"></i>Supprimer
        </button>
      </div>
    </div>
  </div>
</div>
