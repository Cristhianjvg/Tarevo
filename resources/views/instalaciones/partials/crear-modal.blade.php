<div class="sw-modal-backdrop" id="crearInstalacion">
    <div class="sw-modal sw-modal-lg" role="dialog" aria-modal="true" aria-labelledby="crearInstalacionTitle">
        <div class="sw-modal-title">
            <div>
                <div id="crearInstalacionTitle">Agregar instalación</div>
                <p class="sw-card-body">Completa los datos de la nueva instalación.</p>
            </div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('crearInstalacion')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="{{ route('instalaciones.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="sw-field">
                <label class="sw-label" for="inst_nombre">Nombre</label>
                <input class="sw-input @error('nombre') sw-error @enderror"
                       id="inst_nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="inst_direccion">Dirección</label>
                <input class="sw-input @error('direccion') sw-error @enderror"
                       id="inst_direccion" name="direccion" value="{{ old('direccion') }}">
                @error('direccion')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="inst_user_id">Responsable</label>
                <select class="sw-select @error('user_id') sw-error @enderror"
                        id="inst_user_id" name="user_id" required>
                    <option value="">Selecciona un responsable</option>
                    @foreach ($responsables as $responsable)
                        <option value="{{ $responsable->id }}" @selected(old('user_id') == $responsable->id)>
                            {{ $responsable->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="inst_descripcion">Descripción</label>
                <textarea class="sw-input @error('descripcion') sw-error @enderror"
                          id="inst_descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('crearInstalacion')">
                    Cancelar
                </button>
                <button type="submit" class="sw-btn sw-btn-primary">Guardar instalación</button>
            </div>
        </form>
    </div>
</div>
