@extends('layouts.app')

@section('title', 'Mis Canjes')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-receipt text-primary"></i> Mis Canjes
            </h1>
            <p class="text-muted">Historial de recompensas canjeadas</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('usuario.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
            <a href="{{ route('usuario.recompensas.catalogo') }}" class="btn btn-primary">
                <i class="fas fa-gift"></i> Ver Catálogo
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('usuario.mis-canjes') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Filtrar por Estado</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            ⏳ Pendiente
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            ✅ Aprobado
                        </option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                            📦 Entregado
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            ❌ Cancelado
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('usuario.mis-canjes') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo"></i> Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Canjes -->
    <div class="row g-4">
        @forelse($redemptions as $redemption)
        <div class="col-12">
            <div class="card shadow-sm hover-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Imagen de la recompensa -->
                        <div class="col-md-2 text-center">
                            @if($redemption->image)
                                <img src="{{ asset('images/' . $redemption->image) }}" 
                                     alt="{{ $redemption->title }}" 
                                     class="img-fluid rounded"
                                     style="max-height: 120px; object-fit: cover;">
                            @else
                                <div class="reward-placeholder-small mx-auto" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; border-radius: 0.5rem;">
                                    <svg width="40" height="40" fill="white" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Información del canje -->
                        <div class="col-md-6">
                            <h5 class="mb-2">{{ $redemption->title }}</h5>
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ ucfirst($redemption->category) }}</span>
                                @if($redemption->status == 'pending')
                                    <span class="badge bg-warning text-dark">⏳ Pendiente</span>
                                @elseif($redemption->status == 'approved')
                                    <span class="badge bg-success">✅ Aprobado</span>
                                @elseif($redemption->status == 'delivered')
                                    <span class="badge bg-info">📦 Entregado</span>
                                @else
                                    <span class="badge bg-danger">❌ Cancelado</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-calendar"></i> 
                                Canjeado: {{ \Carbon\Carbon::parse($redemption->created_at)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-barcode"></i> 
                                Código: <strong class="text-primary">{{ $redemption->redemption_code }}</strong>
                            </p>
                        </div>

                        <!-- Puntos y acciones -->
                        <div class="col-md-4 text-md-end">
                            <div class="mb-3">
                                <span class="text-muted small">Puntos usados</span>
                                <h4 class="mb-0 text-primary">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="display: inline;">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ number_format($redemption->points_used) }}
                                </h4>
                            </div>
                            <a href="{{ route('usuario.mis-canjes.detalle', $redemption->redemption_id) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <svg width="80" height="80" fill="currentColor" class="text-muted mb-3" viewBox="0 0 16 16">
                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
                    </svg>
                    <h5 class="text-muted">No tienes canjes registrados</h5>
                    <p class="text-muted">Comienza a canjear tus puntos por increíbles recompensas</p>
                    <a href="{{ route('usuario.recompensas.catalogo') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-gift"></i> Explorar Catálogo
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($redemptions->hasPages())
    <div class="mt-4">
        <div class="d-flex justify-content-center">
            {{ $redemptions->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<style>
    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    /* Estilos Gubernamentales para Mis Canjes - Estilo gob.mx */

/* Tipografía similar a gob.mx */
body {
    font-family: 'Montserrat', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
}

/* Página principal */
.container-fluid {
    max-width: 1400px;
}

/* Header de la página */
.row.mb-4 h1 {
    color: #6B1C3D;
    font-weight: 700;
    font-size: 2rem;
}

.row.mb-4 .text-muted {
    color: #6c757d !important;
    font-size: 1rem;
}

/* Iconos en títulos */
.text-primary {
    color: #6B1C3D !important;
}

/* Botones principales */
.btn-primary {
    background-color: #6B1C3D !important;
    border-color: #6B1C3D !important;
    color: white !important;
    font-weight: 600;
    padding: 0.5rem 1.2rem;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #8B2450 !important;
    border-color: #8B2450 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.3);
}

.btn-outline-primary {
    color: #6B1C3D !important;
    border-color: #6B1C3D !important;
    font-weight: 600;
    padding: 0.5rem 1.2rem;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: #6B1C3D !important;
    border-color: #6B1C3D !important;
    color: white !important;
    transform: translateY(-2px);
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
    font-weight: 500;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Tarjetas (Cards) */
.card {
    border: none;
    border-radius: 0.5rem;
    overflow: hidden;
}

.card.shadow-sm {
    box-shadow: 0 2px 8px rgba(107, 28, 61, 0.1) !important;
}

.card-body {
    padding: 1.5rem;
}

/* Card de filtros */
.card-body .form-label {
    color: #6B1C3D;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-select {
    border: 2px solid #e0e0e0;
    border-radius: 0.375rem;
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #6B1C3D;
    box-shadow: 0 0 0 0.2rem rgba(107, 28, 61, 0.15);
    outline: none;
}

/* Cards de canjes con hover effect */
.hover-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(107, 28, 61, 0.2) !important;
    border-left-color: #6B1C3D;
}

/* Imagen de recompensa */
.img-fluid.rounded {
    border: 3px solid #f8f9fa;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Placeholder de imagen */
.reward-placeholder-small {
    background: linear-gradient(135deg, #6B1C3D 0%, #9B3558 100%) !important;
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.3);
}

/* Títulos de recompensas */
.card-body h5 {
    color: #212529;
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
}

/* Badges con colores gubernamentales */
.badge {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 0.25rem;
}

.badge.bg-secondary {
    background-color: #6B1C3D !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

/* Información de fecha y código */
.text-muted.small {
    color: #6c757d !important;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.text-muted.small strong.text-primary {
    color: #6B1C3D !important;
    font-weight: 700;
}

/* Sección de puntos usados */
.text-md-end .text-muted.small {
    display: block;
    color: #6c757d !important;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.text-md-end h4 {
    color: #6B1C3D !important;
    font-weight: 700;
    font-size: 1.75rem;
}

.text-md-end h4 svg {
    vertical-align: middle;
    margin-right: 0.25rem;
}

/* Botón de ver detalles */
.btn-sm {
    padding: 0.4rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Card vacío (sin canjes) */
.card-body.text-center.py-5 {
    padding: 4rem 2rem !important;
}

.card-body.text-center.py-5 h5 {
    color: #6B1C3D;
    font-weight: 600;
    font-size: 1.5rem;
}

.card-body.text-center.py-5 .text-muted {
    font-size: 1.1rem;
    color: #6c757d !important;
}

.card-body.text-center.py-5 svg {
    color: #6B1C3D !important;
    opacity: 0.5;
}

/* Paginación */
.pagination {
    margin-top: 2rem;
}

.pagination .page-link {
    color: #6B1C3D;
    border-color: #dee2e6;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
}

.pagination .page-link:hover {
    background-color: #6B1C3D;
    border-color: #6B1C3D;
    color: white;
}

.pagination .page-item.active .page-link {
    background-color: #6B1C3D;
    border-color: #6B1C3D;
}

/* Iconos de Font Awesome */
.fas, .far {
    margin-right: 0.375rem;
}

/* Espaciado entre elementos */
.row.g-4 {
    row-gap: 1.5rem !important;
}

/* Responsive */
@media (max-width: 768px) {
    .row.mb-4 h1 {
        font-size: 1.5rem;
    }
    
    .col-md-6.text-md-end {
        text-align: left !important;
        margin-top: 1rem;
    }
    
    .btn-primary, .btn-outline-primary {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .text-md-end {
        text-align: left !important;
        margin-top: 1rem;
    }
    
    .hover-card .row {
        text-align: center;
    }
}

/* Animaciones suaves */
* {
    transition: color 0.2s ease, background-color 0.2s ease;
}

/* Mejora de contraste para accesibilidad */
.text-muted {
    color: #495057 !important;
}

/* Bordes y sombras consistentes */
.card {
    border: 1px solid rgba(107, 28, 61, 0.1);
}

/* Hover en imágenes */
.img-fluid.rounded:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* Estilo para el código de barras */
.fa-barcode {
    color: #6B1C3D;
}

/* Links en general */
a {
    text-decoration: none;
    transition: all 0.2s ease;
}

a:hover {
    opacity: 0.85;
}

/* Select personalizado */
select.form-select option {
    padding: 0.5rem;
}

/* Mejora visual del formulario de filtros */
.card-body form {
    align-items: flex-end;
}

.form-label {
    margin-bottom: 0.5rem;
}

/* Estilos adicionales para badges con iconos */
.badge svg, .badge i {
    vertical-align: middle;
    margin-right: 0.25rem;
}

/* Separación entre badges */
.badge + .badge {
    margin-left: 0.5rem;
}

/* Sombra más pronunciada en hover para cards */
.hover-card:hover .img-fluid {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

/* Estilo para códigos */
strong.text-primary {
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
}
</style>
@endsection