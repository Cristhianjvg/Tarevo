@extends('layouts.app')

@section('title', 'Instalaciones')

@section('content')
    <div class="sw-page-header sw-crud-head">
        <div class="sw-crud-title">
            <h1 class="sw-page-title">Gestión de instalaciones</h1>
            <p class="sw-page-subtitle">Instalaciones registradas y su responsable.</p>
        </div>

        <div class="sw-crud-actions">
            <button type="button" class="sw-btn sw-btn-primary" onclick="swOpenModal('crearInstalacion')">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>Agregar instalación</span>
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
                        <th>Dirección</th>
                        <th>Responsable</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($instalaciones as $instalacion)
                        <tr>
                            <td>{{ $instalacion->nombre }}</td>
                            <td>{{ $instalacion->direccion ?? '—' }}</td>
                            <td>{{ $instalacion->responsable->nombre }}</td>
                            <td>{{ $instalacion->descripcion ?? '—' }}</td>
                            <td>
                                <div class="sw-table-actions">
                                    <button type="button" class="sw-bico" title="Editar" aria-label="Editar"
                                            data-action="{{ route('instalaciones.update', $instalacion) }}"
                                            data-id="{{ $instalacion->id }}"
                                            data-nombre="{{ $instalacion->nombre }}"
                                            data-direccion="{{ $instalacion->direccion }}"
                                            data-descripcion="{{ $instalacion->descripcion }}"
                                            data-user-id="{{ $instalacion->user_id }}"
                                            onclick="abrirEditarInstalacion(this)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button type="button" class="sw-bico" title="Eliminar" aria-label="Eliminar"
                                            data-action="{{ route('instalaciones.destroy', $instalacion) }}"
                                            data-nombre="{{ $instalacion->nombre }}"
                                            onclick="abrirEliminarInstalacion(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay instalaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{ $instalaciones->links() }}

    @include('instalaciones.partials.crear-modal')
    @include('instalaciones.partials.editar-modal')
    @include('instalaciones.partials.eliminar-modal')

    @push('scripts')
        <script>
            function abrirEditarInstalacion(btn) {
                document.getElementById('editarInstalacionForm').action = btn.dataset.action;
                document.getElementById('edit_inst_id').value = btn.dataset.id;
                document.getElementById('edit_inst_nombre').value = btn.dataset.nombre;
                document.getElementById('edit_inst_direccion').value = btn.dataset.direccion;
                document.getElementById('edit_inst_descripcion').value = btn.dataset.descripcion;
                document.getElementById('edit_inst_user_id').value = btn.dataset.userId;
                swOpenModal('editarInstalacion');
            }

            function abrirEliminarInstalacion(btn) {
                document.getElementById('eliminarInstalacionForm').action = btn.dataset.action;
                document.getElementById('eliminarInstalacionNombre').textContent = btn.dataset.nombre;
                swOpenModal('eliminarInstalacion');
            }
        </script>
    @endpush

    @if ($errors->any())
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    swOpenModal('{{ old('_modal') === 'editar' ? 'editarInstalacion' : 'crearInstalacion' }}');
                });
            </script>
        @endpush
    @endif
@endsection
