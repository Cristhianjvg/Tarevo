@extends('layouts.app')

@section('title', 'Tareas')

@section('content')
    <div class="sw-page-header sw-crud-head">
        <div class="sw-crud-title">
            <h1 class="sw-page-title">Gestión de tareas</h1>
            <p class="sw-page-subtitle">Tareas registradas en el sistema.</p>
        </div>

        <div class="sw-crud-actions">
            <a href="{{ route('tareas.create') }}" class="sw-btn sw-btn-primary">
                <i class="fa-solid fa-tasks" aria-hidden="true"></i>
                <span>Agregar tareas</span>
            </a>
        </div>
    </div>

    <section class="sw-card sw-list-card">
        <div class="sw-table-wrap sw-list-table-wrap">
            <table class="sw-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Responsable</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tareas as $tarea)
                        <tr>
                            <td>{{ $tarea->titulo }}</td>
                            <td>{{ $tarea->descripcion }}</td>
                            <td>{{ $tarea->responsable }}</td>
                            <td>
                                @foreach ($tarea->roles as $rol)
                                    <span class="sw-badge sw-badge-info">{{ $rol->nombre }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay tareas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{ $tareas->links() }}
@endsection
