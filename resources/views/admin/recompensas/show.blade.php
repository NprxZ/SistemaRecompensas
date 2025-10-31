@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-gift me-2"></i>Detalle de Recompensa</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.recompensas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <a href="{{ route('admin.recompensas.edit', $reward->reward_id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Información de la Recompensa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($reward->image)
                                <img src="{{ asset('storage/' . $reward->image) }}" 
                                     alt="{{ $reward->title }}" 
                                     class="img-fluid rounded shadow">
                            @else
                                <div class="bg-secondary text-white p-5 rounded">
                                    <i class="fas fa-gift fa-5x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $reward->title }}</h3>
                            <p class="text-muted">{{ $reward->description }}</p>

                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">ID:</th>
                                    <td>{{ $reward->reward_id }}</td>
                                </tr>
                                <tr>
                                    <th>Categoría:</th>
                                    <td><span class="badge bg-info">{{ ucfirst($reward->category) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Puntos Requeridos:</th>
                                    <td>
                                        <strong class="text-warning fs-5">{{ $reward->points_required }}</strong>
                                        <i class="fas fa-coins text-warning"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stock Disponible:</th>
                                    <td>
                                        @if($reward->stock > 10)
                                            <span class="badge bg-success fs-6">{{ $reward->stock }} unidades</span>
                                        @elseif($reward->stock > 0)
                                            <span class="badge bg-warning fs-6">{{ $reward->stock }} unidades</span>
                                        @else
                                            <span class="badge bg-danger fs-6">Agotado</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        @if($reward->active)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha de Expiración:</th>
                                    <td>
                                        @if($reward->expiration_date)
                                            {{ \Carbon\Carbon::parse($reward->expiration_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Sin expiración</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Creado:</th>
                                    <td>{{ \Carbon\Carbon::parse($reward->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($reward->terms_conditions)
                        <hr>
                        <h5>Términos y Condiciones</h5>
                        <p class="text-muted">{{ $reward->terms_conditions }}</p>
                    @endif>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Total de Canjes</h6>
                        <h2 class="text-primary">{{ $stats['total_redemptions'] }}</h2>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6>Canjes Pendientes</h6>
                        <h3 class="text-warning">{{ $stats['pending_redemptions'] }}</h3>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6>Puntos Utilizados</h6>
                        <h3 class="text-success">{{ number_format($stats['total_points_used']) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.recompensas.toggle-active', $reward->reward_id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-{{ $reward->active ? 'secondary' : 'success' }} w-100">
                            <i class="fas fa-power-off me-2"></i>
                            {{ $reward->active ? 'Desactivar' : 'Activar' }} Recompensa
                        </button>
                    </form>

                    <a href="{{ route('admin.recompensas.edit', $reward->reward_id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Editar Recompensa
                    </a>

                    <a href="{{ route('admin.canjes.index', ['reward_id' => $reward->reward_id]) }}" class="btn btn-info w-100">
                        <i class="fas fa-list me-2"></i>Ver Canjes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection