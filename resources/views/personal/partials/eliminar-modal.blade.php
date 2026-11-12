<div class="sw-modal-backdrop" id="eliminarPersonal">
    <div class="sw-modal" role="dialog" aria-modal="true" aria-labelledby="eliminarPersonalTitle">
        <div class="sw-modal-title">
            <div id="eliminarPersonalTitle">Eliminar personal</div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('eliminarPersonal')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="eliminarPersonalForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <p class="sw-card-body">
                ¿Seguro que quieres eliminar a <strong id="eliminarPersonalNombre"></strong>?
                Esta acción no se puede deshacer.
            </p>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('eliminarPersonal')">Cancelar</button>
                <button type="submit" class="sw-btn sw-btn-error">Eliminar</button>
            </div>
        </form>
    </div>
</div>
