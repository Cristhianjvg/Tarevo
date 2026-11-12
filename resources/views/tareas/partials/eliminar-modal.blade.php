<div class="sw-modal-backdrop" id="eliminarTarea">
    <div class="sw-modal" role="dialog" aria-modal="true" aria-labelledby="eliminarTareaTitle">
        <div class="sw-modal-title">
            <div id="eliminarTareaTitle">Eliminar tarea</div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('eliminarTarea')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="eliminarTareaForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <p class="sw-card-body">
                ¿Seguro que quieres eliminar la tarea <strong id="eliminarTareaNombre"></strong>?
                Esta acción no se puede deshacer.
            </p>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('eliminarTarea')">Cancelar</button>
                <button type="submit" class="sw-btn sw-btn-error">Eliminar</button>
            </div>
        </form>
    </div>
</div>
