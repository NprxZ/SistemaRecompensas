@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-gift me-2"></i>Gestión de Recompensas</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.recompensas.create') }}" class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Nueva Recompensa
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.recompensas.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Buscar recompensa..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Categoría</label>
                        <select name="category" class="form-select">
                            <option value="all">Todas</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="active" class="form-select">
                            <option value="all">Todos</option>
                            <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Recompensas -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Puntos</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rewards as $reward)
                        <tr>
                            <td>{{ $reward->reward_id }}</td>
                            <td>
                                @if($reward->image)
                                    <img src="{{ asset('storage/' . $reward->image) }}" 
                                         alt="{{ $reward->title }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                @else
                                    <div class="bg-secondary text-white text-center" 
                                         style="width: 50px; height: 50px; border-radius: 5px; line-height: 50px;">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $reward->title }}</strong><br>
                                <small class="text-muted">{{ Str::limit($reward->description, 50) }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ ucfirst($reward->category) }}</span></td>
                            <td>
                                <strong class="text-warning">{{ $reward->points_required }}</strong>
                                <i class="fas fa-coins text-warning"></i>
                            </td>
                            <td>
                                @if($reward->stock > 10)
                                    <span class="badge bg-success">{{ $reward->stock }}</span>
                                @elseif($reward->stock > 0)
                                    <span class="badge bg-warning">{{ $reward->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Agotado</span>
                                @endif
                            </td>
                            <td>
                                @if($reward->active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.recompensas.show', $reward->reward_id) }}" 
                                       class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.recompensas.edit', $reward->reward_id) }}" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.recompensas.toggle-active', $reward->reward_id) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" 
                                                title="{{ $reward->active ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No se encontraron recompensas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $rewards->links() }}
            </div>
        </div>
    </div>
</div>
@endsection