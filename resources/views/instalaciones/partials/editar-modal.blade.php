@php
    $reopenEdit = old('_modal') === 'editar';
@endphp
<div class="sw-modal-backdrop" id="editarInstalacion">
    <div class="sw-modal sw-modal-lg" role="dialog" aria-modal="true" aria-labelledby="editarInstalacionTitle">
        <div class="sw-modal-title">
            <div>
                <div id="editarInstalacionTitle">Editar instalación</div>
                <p class="sw-card-body">Actualiza los datos de la instalación.</p>
            </div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('editarInstalacion')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="editarInstalacionForm" method="POST"
              action="{{ $reopenEdit ? route('instalaciones.update', old('_instalacion_id')) : '' }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="_modal" value="editar">
            <input type="hidden" name="_instalacion_id" id="edit_inst_id" value="{{ $reopenEdit ? old('_instalacion_id') : '' }}">

            <div class="sw-field">
                <label class="sw-label" for="edit_inst_nombre">Nombre</label>
                <input class="sw-input @if ($reopenEdit) @error('nombre') sw-error @enderror @endif"
                       id="edit_inst_nombre" name="nombre" value="{{ $reopenEdit ? old('nombre') : '' }}" required>
                @if ($reopenEdit) @error('nombre')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_inst_direccion">Dirección</label>
                <input class="sw-input @if ($reopenEdit) @error('direccion') sw-error @enderror @endif"
                       id="edit_inst_direccion" name="direccion" value="{{ $reopenEdit ? old('direccion') : '' }}">
                @if ($reopenEdit) @error('direccion')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_inst_user_id">Responsable</label>
                <select class="sw-select @if ($reopenEdit) @error('user_id') sw-error @enderror @endif"
                        id="edit_inst_user_id" name="user_id" required>
                    @foreach ($responsables as $responsable)
                        <option value="{{ $responsable->id }}" @selected($reopenEdit && old('user_id') == $responsable->id)>
                            {{ $responsable->nombre }}
                        </option>
                    @endforeach
                </select>
                @if ($reopenEdit) @error('user_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_inst_descripcion">Descripción</label>
                <textarea class="sw-input @if ($reopenEdit) @error('descripcion') sw-error @enderror @endif"
                          id="edit_inst_descripcion" name="descripcion" rows="3">{{ $reopenEdit ? old('descripcion') : '' }}</textarea>
                @if ($reopenEdit) @error('descripcion')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('editarInstalacion')">Cancelar</button>
                <button type="submit" class="sw-btn sw-btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
