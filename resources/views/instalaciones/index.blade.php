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

    <section class="sw-card sw-list-card">
        <div class="sw-table-wrap sw-list-table-wrap">
            <table class="sw-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Responsable</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($instalaciones as $instalacion)
                        <tr>
                            <td>{{ $instalacion->nombre }}</td>
                            <td>{{ $instalacion->direccion ?? '—' }}</td>
                            <td>{{ $instalacion->responsable->nombre }}</td>
                            <td>{{ $instalacion->descripcion ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay instalaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{ $instalaciones->links() }}

    @include('instalaciones.partials.crear-modal')
@if ($errors->any())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                swOpenModal('crearInstalacion');
            });
        </script>
    @endpush
@endif
@endsection
