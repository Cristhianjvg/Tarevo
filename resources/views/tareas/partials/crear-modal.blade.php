<div class="sw-modal-backdrop" id="crearTarea">
    <div class="sw-modal sw-modal-lg" role="dialog" aria-modal="true" aria-labelledby="crearTareaTitle">
        <div class="sw-modal-title">
            <div>
                <div id="crearTareaTitle">Agregar tarea</div>
                <p class="sw-card-body">Asigna la tarea a una instalación y a un responsable.</p>
            </div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('crearTarea')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="{{ route('tareas.store') }}" method="POST">
            @csrf

            <div class="sw-field">
                <label class="sw-label" for="tarea_titulo">Título</label>
                <input class="sw-input @error('titulo') sw-error @enderror" id="tarea_titulo" name="titulo" maxlength="255" value="{{ old('titulo') }}" required>
                @error('titulo')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="tarea_descripcion">Descripción</label>
                <textarea class="sw-input @error('descripcion') sw-error @enderror" id="tarea_descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="tarea_instalacion_id">Instalación</label>
                <select class="sw-select @error('instalacion_id') sw-error @enderror" id="tarea_instalacion_id" name="instalacion_id" required>
                    <option value="">Selecciona una instalación</option>
                    @foreach ($instalaciones as $instalacion)
                        <option value="{{ $instalacion->id }}" @selected(old('instalacion_id') == $instalacion->id)>{{ $instalacion->nombre }}</option>
                    @endforeach
                </select>
                @error('instalacion_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="tarea_tipo_tarea_id">Tipo de tarea</label>
                <select class="sw-select @error('tipo_tarea_id') sw-error @enderror" id="tarea_tipo_tarea_id" name="tipo_tarea_id" required>
                    <option value="">Selecciona un tipo</option>
                    @foreach ($tiposTarea as $tipo)
                        <option value="{{ $tipo->id }}" @selected(old('tipo_tarea_id') == $tipo->id)>{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
                @error('tipo_tarea_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="tarea_asignada_a">Responsable</label>
                <select class="sw-select @error('asignada_a_user_id') sw-error @enderror" id="tarea_asignada_a" name="asignada_a_user_id" required>
                    <option value="">Selecciona un responsable</option>
                    @foreach ($responsables as $responsable)
                        <option value="{{ $responsable->id }}" @selected(old('asignada_a_user_id') == $responsable->id)>{{ $responsable->nombre }}</option>
                    @endforeach
                </select>
                @error('asignada_a_user_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="tarea_programada_para">Fecha y hora programada</label>
                <input class="sw-input @error('programada_para') sw-error @enderror" id="tarea_programada_para" name="programada_para" type="datetime-local" value="{{ old('programada_para') }}">
                @error('programada_para')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('crearTarea')">Cancelar</button>
                <button type="submit" class="sw-btn sw-btn-primary">Guardar tarea</button>
            </div>
        </form>
    </div>
</div>
