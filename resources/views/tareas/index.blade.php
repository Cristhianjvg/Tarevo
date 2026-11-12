@extends('layouts.app')

@section('title', 'Tareas')

@section('content')
    @php
        $estados = ['pendiente' => 'Pendiente', 'en_progreso' => 'En progreso', 'completada' => 'Completada', 'cancelada' => 'Cancelada'];
    @endphp

    <div class="sw-page-header sw-crud-head">
        <div class="sw-crud-title">
            <h1 class="sw-page-title">Gestión de tareas</h1>
            <p class="sw-page-subtitle">
                Tareas registradas, con su instalación y responsable.
            </p>
        </div>

        <div class="sw-crud-actions">
            <button type="button" class="sw-btn sw-btn-primary" onclick="swOpenModal('crearTarea')">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>Agregar tarea</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="sw-alert sw-alert-success">{{ session('success') }}</div>
    @endif

    <section class="sw-card sw-list-card">
        <div class="sw-table-wrap sw-list-table-wrap">
            <table class="sw-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Instalación</th>
                        <th>Tipo</th>
                        <th>Responsable</th>
                        <th>Programada para</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tareas as $tarea)
                        <tr>
                            <td>
                                {{ $tarea->titulo }}
                                @if ($tarea->descripcion)
                                    <small class="sw-hint">{{ $tarea->descripcion }}</small>
                                @endif
                            </td>
                            <td>{{ $tarea->instalacion->nombre }}</td>
                            <td>{{ $tarea->tipoTarea->nombre }}</td>
                            <td>{{ $tarea->responsable?->nombre ?? 'Sin asignar' }}</td>
                            <td>{{ $tarea->programada_para?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td><span class="sw-badge sw-badge-info">{{ $estados[$tarea->estado] ?? $tarea->estado }}</span></td>
                            <td>
                                <div class="sw-table-actions">
                                    <button type="button" class="sw-bico" title="Editar" aria-label="Editar"
                                            data-action="{{ route('tareas.update', $tarea) }}"
                                            data-id="{{ $tarea->id }}"
                                            data-titulo="{{ $tarea->titulo }}"
                                            data-descripcion="{{ $tarea->descripcion }}"
                                            data-instalacion-id="{{ $tarea->instalacion_id }}"
                                            data-tipo-tarea-id="{{ $tarea->tipo_tarea_id }}"
                                            data-asignada-a="{{ $tarea->asignada_a_user_id }}"
                                            data-programada-para="{{ $tarea->programada_para?->format('Y-m-d\TH:i') }}"
                                            data-estado="{{ $tarea->estado }}"
                                            onclick="abrirEditarTarea(this)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button type="button" class="sw-bico" title="Eliminar" aria-label="Eliminar"
                                            data-action="{{ route('tareas.destroy', $tarea) }}"
                                            data-nombre="{{ $tarea->titulo }}"
                                            onclick="abrirEliminarTarea(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No hay tareas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{ $tareas->links() }}

    @include('tareas.partials.crear-modal')
    @include('tareas.partials.editar-modal')
    @include('tareas.partials.eliminar-modal')

    @push('scripts')
        <script>
            function abrirEditarTarea(btn) {
                document.getElementById('editarTareaForm').action = btn.dataset.action;
                document.getElementById('edit_tarea_id').value = btn.dataset.id;
                document.getElementById('edit_tarea_titulo').value = btn.dataset.titulo;
                document.getElementById('edit_tarea_descripcion').value = btn.dataset.descripcion;
                document.getElementById('edit_tarea_instalacion_id').value = btn.dataset.instalacionId;
                document.getElementById('edit_tarea_tipo_tarea_id').value = btn.dataset.tipoTareaId;
                document.getElementById('edit_tarea_asignada_a').value = btn.dataset.asignadaA;
                document.getElementById('edit_tarea_programada_para').value = btn.dataset.programadaPara;
                document.getElementById('edit_tarea_estado').value = btn.dataset.estado;
                swOpenModal('editarTarea');
            }

            function abrirEliminarTarea(btn) {
                document.getElementById('eliminarTareaForm').action = btn.dataset.action;
                document.getElementById('eliminarTareaNombre').textContent = btn.dataset.nombre;
                swOpenModal('eliminarTarea');
            }
        </script>
    @endpush

    @if ($errors->any())
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    swOpenModal('{{ old('_modal') === 'editar' ? 'editarTarea' : 'crearTarea' }}');
                });
            </script>
        @endpush
    @endif
@endsection
