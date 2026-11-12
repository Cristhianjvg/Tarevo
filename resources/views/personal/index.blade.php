@extends('layouts.app')

@section('title', 'Personal')

@section('content')
    <div class="sw-page-header sw-crud-head">
        <div class="sw-crud-title">
            <h1 class="sw-page-title">Gestión del personal</h1>
            <p class="sw-page-subtitle">Usuarios registrados en el sistema.</p>
        </div>

        <div class="sw-crud-actions">
            <a href="{{ route('personal.create') }}" class="sw-btn sw-btn-primary">
                <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                <span>Agregar personal</span>
            </a>
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
