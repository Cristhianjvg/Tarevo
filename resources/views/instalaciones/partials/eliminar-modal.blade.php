<div class="sw-modal-backdrop" id="eliminarInstalacion">
    <div class="sw-modal" role="dialog" aria-modal="true" aria-labelledby="eliminarInstalacionTitle">
        <div class="sw-modal-title">
            <div id="eliminarInstalacionTitle">Eliminar instalación</div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('eliminarInstalacion')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="eliminarInstalacionForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <p class="sw-card-body">
                ¿Seguro que quieres eliminar la instalación <strong id="eliminarInstalacionNombre"></strong>?
                Esta acción no se puede deshacer.
            </p>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('eliminarInstalacion')">Cancelar</button>
                <button type="submit" class="sw-btn sw-btn-error">Eliminar</button>
            </div>
        </form>
    </div>
</div>
