@php
    $reopenEdit = old('_modal') === 'editar';
@endphp
<div class="sw-modal-backdrop" id="editarTarea">
    <div class="sw-modal sw-modal-lg" role="dialog" aria-modal="true" aria-labelledby="editarTareaTitle">
        <div class="sw-modal-title">
            <div>
                <div id="editarTareaTitle">Editar tarea</div>
                <p class="sw-card-body">Actualiza los datos de la tarea.</p>
            </div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('editarTarea')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="editarTareaForm" method="POST"
              action="{{ $reopenEdit ? route('tareas.update', old('_tarea_id')) : '' }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="_modal" value="editar">
            <input type="hidden" name="_tarea_id" id="edit_tarea_id" value="{{ $reopenEdit ? old('_tarea_id') : '' }}">

            <div class="sw-field">
                <label class="sw-label" for="edit_tarea_titulo">Título</label>
                <input class="sw-input @if ($reopenEdit) @error('titulo') sw-error @enderror @endif" id="edit_tarea_titulo" name="titulo" maxlength="255" value="{{ $reopenEdit ? old('titulo') : '' }}" required>
                @if ($reopenEdit) @error('titulo')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_tarea_descripcion">Descripción</label>
                <textarea class="sw-input @if ($reopenEdit) @error('descripcion') sw-error @enderror @endif" id="edit_tarea_descripcion" name="descripcion" rows="3">{{ $reopenEdit ? old('descripcion') : '' }}</textarea>
                @if ($reopenEdit) @error('descripcion')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_tarea_instalacion_id">Instalación</label>
                <select class="sw-select @if ($reopenEdit) @error('instalacion_id') sw-error @enderror @endif" id="edit_tarea_instalacion_id" name="instalacion_id" required>
                    <option value="">Selecciona una instalación</option>
                    @foreach ($instalaciones as $instalacion)
                        <option value="{{ $instalacion->id }}" @selected($reopenEdit && old('instalacion_id') == $instalacion->id)>{{ $instalacion->nombre }}</option>
                    @endforeach
                </select>
                @if ($reopenEdit) @error('instalacion_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_tarea_tipo_tarea_id">Tipo de tarea</label>
                <select class="sw-select @if ($reopenEdit) @error('tipo_tarea_id') sw-error @enderror @endif" id="edit_tarea_tipo_tarea_id" name="tipo_tarea_id" required>
                    <option value="">Selecciona un tipo</option>
                    @foreach ($tiposTarea as $tipo)
                        <option value="{{ $tipo->id }}" @selected($reopenEdit && old('tipo_tarea_id') == $tipo->id)>{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
                @if ($reopenEdit) @error('tipo_tarea_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_tarea_asignada_a">Responsable</label>
                <select class="sw-select @if ($reopenEdit) @error('asignada_a_user_id') sw-error @enderror @endif" id="edit_tarea_asignada_a" name="asignada_a_user_id" required>
                    <option value="">Selecciona un responsable</option>
                    @foreach ($responsables as $responsable)
                        <option value="{{ $responsable->id }}" @selected($reopenEdit && old('asignada_a_user_id') == $responsable->id)>{{ $responsable->nombre }}</option>
                    @endforeach
                </select>
                @if ($reopenEdit) @error('asignada_a_user_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_tarea_programada_para">Fecha y hora programada</label>
                <input class="sw-input @if ($reopenEdit) @error('programada_para') sw-error @enderror @endif" id="edit_tarea_programada_para" name="programada_para" type="datetime-local" value="{{ $reopenEdit ? old('programada_para') : '' }}">
                @if ($reopenEdit) @error('programada_para')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_tarea_estado">Estado</label>
                <select class="sw-select @if ($reopenEdit) @error('estado') sw-error @enderror @endif" id="edit_tarea_estado" name="estado" required>
                    @foreach (['pendiente' => 'Pendiente', 'en_progreso' => 'En progreso', 'completada' => 'Completada', 'cancelada' => 'Cancelada'] as $valor => $etiqueta)
                        <option value="{{ $valor }}" @selected($reopenEdit && old('estado') === $valor)>{{ $etiqueta }}</option>
                    @endforeach
                </select>
                @if ($reopenEdit) @error('estado')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('editarTarea')">Cancelar</button>
                <button type="submit" class="sw-btn sw-btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
