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

    <section class="sw-card sw-list-card">
        <div class="sw-table-wrap sw-list-table-wrap">
            <table class="sw-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay personal registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{ $personal->links() }}
@endsection
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
                <label class="sw-label" for="roles">Rol</label>
                <select class="sw-select @error('roles') sw-error @enderror"
                        id="roles" name="roles[]" multiple required>
                    <option value="1" disabled selected>Encargado Operativo</option>
                </select>
                @error('roles')
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

@if ($errors->any())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                swOpenModal('crearPersonal');
            });
        </script>
    @endpush
@endif
