@php
    $reopenEdit = old('_modal') === 'editar';
@endphp
<div class="sw-modal-backdrop" id="editarPersonal">
    <div class="sw-modal sw-modal-lg" role="dialog" aria-modal="true" aria-labelledby="editarPersonalTitle">
        <div class="sw-modal-title">
            <div>
                <div id="editarPersonalTitle">Editar personal</div>
                <p class="sw-card-body">Deja la contraseña vacía para conservar la actual.</p>
            </div>

            <button type="button" class="sw-modal-close" onclick="swCloseModal('editarPersonal')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="editarPersonalForm" method="POST"
              action="{{ $reopenEdit ? route('personal.update', old('_user_id')) : '' }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="_modal" value="editar">
            <input type="hidden" name="_user_id" id="edit_user_id" value="{{ $reopenEdit ? old('_user_id') : '' }}">

            <div class="sw-field">
                <label class="sw-label" for="edit_nombre">Nombre</label>
                <input class="sw-input @if ($reopenEdit) @error('nombre') sw-error @enderror @endif"
                       id="edit_nombre" name="nombre" value="{{ $reopenEdit ? old('nombre') : '' }}" required>
                @if ($reopenEdit) @error('nombre')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_username">Usuario</label>
                <input class="sw-input @if ($reopenEdit) @error('username') sw-error @enderror @endif"
                       id="edit_username" name="username" value="{{ $reopenEdit ? old('username') : '' }}" required>
                @if ($reopenEdit) @error('username')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_email">Correo</label>
                <input class="sw-input @if ($reopenEdit) @error('email') sw-error @enderror @endif"
                       id="edit_email" name="email" type="email" value="{{ $reopenEdit ? old('email') : '' }}" required>
                @if ($reopenEdit) @error('email')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_rol_id">Rol</label>
                <select class="sw-select @if ($reopenEdit) @error('rol_id') sw-error @enderror @endif"
                        id="edit_rol_id" name="rol_id" required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}" @selected($reopenEdit && old('rol_id') == $rol->id)>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
                @if ($reopenEdit) @error('rol_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_password">Nueva contraseña</label>
                <input class="sw-input @if ($reopenEdit) @error('password') sw-error @enderror @endif"
                       id="edit_password" name="password" type="password" autocomplete="new-password">
                @if ($reopenEdit) @error('password')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror @endif
            </div>

            <div class="sw-field">
                <label class="sw-label" for="edit_password_confirmation">Confirmar nueva contraseña</label>
                <input class="sw-input" id="edit_password_confirmation" name="password_confirmation"
                       type="password" autocomplete="new-password">
            </div>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost" onclick="swCloseModal('editarPersonal')">Cancelar</button>
                <button type="submit" class="sw-btn sw-btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
