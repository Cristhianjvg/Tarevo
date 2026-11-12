@extends('layouts.app')

@section('title', 'Personal')

@section('content')
    <div class="sw-page-header sw-crud-head">
        <div class="sw-crud-title">
            <h1 class="sw-page-title">Gestión del personal</h1>
            <p class="sw-page-subtitle">Usuarios registrados en el sistema.</p>
        </div>

        <div class="sw-crud-actions">
           <button type="button"
                    class="sw-btn sw-btn-primary"
                    onclick="swOpenModal('crearPersonal')">
                <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                <span>Agregar personal</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="sw-alert sw-alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="sw-alert sw-alert-error">{{ session('error') }}</div>
    @endif

    <section class="sw-card sw-list-card">
        <div class="sw-table-wrap sw-list-table-wrap">
            <table class="sw-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($personal as $persona)
                        <tr>
                            <td>{{ $persona->nombre }}</td>
                            <td>{{ $persona->username }}</td>
                            <td>{{ $persona->email }}</td>
                            <td>
                                @foreach ($persona->roles as $rol)
                                    <span class="sw-badge sw-badge-info">{{ $rol->nombre }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="sw-table-actions">
                                    <button type="button" class="sw-bico" title="Editar" aria-label="Editar"
                                            data-action="{{ route('personal.update', $persona) }}"
                                            data-id="{{ $persona->id }}"
                                            data-nombre="{{ $persona->nombre }}"
                                            data-username="{{ $persona->username }}"
                                            data-email="{{ $persona->email }}"
                                            data-rol-id="{{ $persona->roles->first()?->id }}"
                                            onclick="abrirEditarPersonal(this)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    @if ($persona->id !== auth()->id())
                                        <button type="button" class="sw-bico" title="Eliminar" aria-label="Eliminar"
                                                data-action="{{ route('personal.destroy', $persona) }}"
                                                data-nombre="{{ $persona->nombre }}"
                                                onclick="abrirEliminarPersonal(this)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay personal registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{ $personal->links() }}
<div class="sw-modal-backdrop" id="crearPersonal">
    <div class="sw-modal sw-modal-lg"
         role="dialog"
         aria-modal="true"
         aria-labelledby="crearPersonalTitle">
        <div class="sw-modal-title">
            <div>
                <div id="crearPersonalTitle">Agregar personal</div>
                <p class="sw-card-body">Completa los datos de la nueva persona.</p>
            </div>

            <button type="button"
                    class="sw-modal-close"
                    onclick="swCloseModal('crearPersonal')"
                    aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="{{ route('personal.store') }}" method="POST">
            @csrf

            <div class="sw-field">
                <label class="sw-label" for="nombre">Nombre</label>
                <input class="sw-input @error('nombre') sw-error @enderror"
                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="username">Usuario</label>
                <input class="sw-input @error('username') sw-error @enderror"
                       id="username" name="username" value="{{ old('username') }}" required>
                @error('username')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="email">Correo</label>
                <input class="sw-input @error('email') sw-error @enderror"
                       id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>


            <div class="sw-field">
                <label class="sw-label" for="password">Contraseña</label>
                <input class="sw-input @error('password') sw-error @enderror"
                       id="password" name="password" type="password" required>
                @error('password')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="password_confirmation">Confirmar contraseña</label>
                <input
                    class="sw-input @error('password_confirmation') sw-error @enderror"
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    required
                >
                @error('password_confirmation')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-field">
                <label class="sw-label" for="rol_id">Rol</label>
                <select class="sw-select @error('rol_id') sw-error @enderror"
                        id="rol_id" name="rol_id" required>
                    <option value="">Selecciona un rol</option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}" @selected(old('rol_id') == $rol->id)>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('rol_id')
                    <span class="sw-hint-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="sw-form-actions">
                <button type="button" class="sw-btn sw-btn-ghost"
                        onclick="swCloseModal('crearPersonal')">
                    Cancelar
                </button>
                <button type="submit" class="sw-btn sw-btn-primary">
                    Guardar personal
                </button>
            </div>
        </form>
    </div>
</div>

@include('personal.partials.editar-modal')
@include('personal.partials.eliminar-modal')

@push('scripts')
    <script>
        function abrirEditarPersonal(btn) {
            var form = document.getElementById('editarPersonalForm');
            form.action = btn.dataset.action;
            document.getElementById('edit_user_id').value = btn.dataset.id;
            document.getElementById('edit_nombre').value = btn.dataset.nombre;
            document.getElementById('edit_username').value = btn.dataset.username;
            document.getElementById('edit_email').value = btn.dataset.email;
            document.getElementById('edit_rol_id').value = btn.dataset.rolId;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            swOpenModal('editarPersonal');
        }

        function abrirEliminarPersonal(btn) {
            document.getElementById('eliminarPersonalForm').action = btn.dataset.action;
            document.getElementById('eliminarPersonalNombre').textContent = btn.dataset.nombre;
            swOpenModal('eliminarPersonal');
        }
    </script>
@endpush

@if ($errors->any())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                swOpenModal('{{ old('_modal') === 'editar' ? 'editarPersonal' : 'crearPersonal' }}');
            });
        </script>
    @endpush
@endif
@endsection
