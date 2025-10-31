@extends('layouts.app')

@section('title', 'Catálogo de Recompensas')

@section('content')
<div class="container-fluid py-4">
    <!-- Header con puntos del usuario -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-2">
                <i class="fas fa-gift text-primary"></i> Catálogo de Recompensas
            </h1>
            <p class="text-muted">Canjea tus puntos por increíbles recompensas</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="card bg-primary text-white">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small">Tus puntos:</span>
                        <h4 class="mb-0 fw-bold">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="display: inline;">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ number_format($user->points) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('usuario.recompensas.catalogo') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Categoría</label>
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        <option value="descuentos" {{ request('category') == 'descuentos' ? 'selected' : '' }}>
                            💰 Descuentos
                        </option>
                        <option value="servicios" {{ request('category') == 'servicios' ? 'selected' : '' }}>
                            🔧 Servicios
                        </option>
                        <option value="productos" {{ request('category') == 'productos' ? 'selected' : '' }}>
                            🎁 Productos
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Ordenar por</label>
                    <select name="order" class="form-select" onchange="this.form.submit()">
                        <option value="points_asc" {{ request('order') == 'points_asc' ? 'selected' : '' }}>
                            Puntos: Menor a Mayor
                        </option>
                        <option value="points_desc" {{ request('order') == 'points_desc' ? 'selected' : '' }}>
                            Puntos: Mayor a Menor
                        </option>
                        <option value="newest" {{ request('order') == 'newest' ? 'selected' : '' }}>
                            Más Recientes
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Disponibilidad</label>
                    <select name="available" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        <option value="yes" {{ request('available') == 'yes' ? 'selected' : '' }}>
                            Solo Disponibles
                        </option>
                        <option value="affordable" {{ request('available') == 'affordable' ? 'selected' : '' }}>
                            Que Puedo Canjear
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <a href="{{ route('usuario.recompensas.catalogo') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo"></i> Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Catálogo -->
    <div class="cuadricula_contenido">
        @forelse($rewards as $reward)
        <div class="seccion_artista">
            <div class="contenedor_albumes edit_artistas" style="border-radius:0.4rem;">
                <div class="animacion_carta" style="border-radius:0.4rem;">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#rewardModal{{ $reward->reward_id }}">
                        @if($reward->image)
                            <img width="100%" src="{{ asset('images/' . $reward->image) }}" alt="{{ $reward->title }}" style="border-radius:0.4rem 0.4rem 0 0;">
                        @else
                            <div class="reward-placeholder" style="width: 100%; height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; border-radius:0.4rem 0.4rem 0 0;">
                                <svg width="80" height="80" fill="white" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="efecto_imagen">
                            <svg width="60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                        
                        <!-- Badge de categoría -->
                        <div class="marca_album marca_artista">
                            <span class="badge bg-dark">{{ ucfirst($reward->category) }}</span>
                        </div>
                        
                        <!-- Badge de disponibilidad -->
                        <div class="marca_formato formato_artista">
                            @if($reward->stock > 5)
                                <span class="badge bg-success">Disponible</span>
                            @elseif($reward->stock > 0)
                                <span class="badge bg-warning text-dark">Últimas unidades</span>
                            @else
                                <span class="badge bg-danger">Agotado</span>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
            
            <div style="margin:10px;">
                <span style="font-weight: bold;">{{ $reward->title }}</span>
            </div>
            
            <div style="margin:10px; font-size: 0.9rem; color: #666;">
                <div>
                    <svg width="16" height="16" fill="currentColor" class="text-warning" viewBox="0 0 20 20" style="display: inline;">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ number_format($reward->points_required) }} puntos
                </div>
                <div><i class="fas fa-boxes"></i> Stock: {{ $reward->stock }} unidades</div>
                <div class="text-truncate" style="max-width: 100%;">
                    <i class="fas fa-align-left"></i> {{ Str::limit($reward->description, 50) }}
                </div>
            </div>

            <div style="text-align:center; margin: 10px;">
                <button class="btn btn-sm btn-primary w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#rewardModal{{ $reward->reward_id }}">
                    <i class="fas fa-info-circle"></i> Ver Detalles
                </button>
            </div>
        </div>

        <!-- MODAL CON DETALLES COMPLETOS -->
        <div class="modal fade" id="rewardModal{{ $reward->reward_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-gift"></i> {{ $reward->title }}
                            <span class="badge bg-light text-dark ms-2">{{ ucfirst($reward->category) }}</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Imagen destacada -->
                        <div class="text-center mb-4">
                            @if($reward->image)
                        <img src="{{ asset('images/' . $reward->image) }}" 
                            alt="{{ $reward->title }}" 
                            class="img-fluid rounded shadow"
                            style="max-height: 350px; position: relative; z-index: 10 !important;">

                            @else
                                <div class="reward-placeholder-modal mx-auto" style="width: 100%; max-width: 400px; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; border-radius: 0.5rem;">
                                    <svg width="120" height="120" fill="white" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">
                                <i class="fas fa-align-left"></i> Descripción
                            </h6>
                            <p class="text-muted">{{ $reward->description }}</p>
                        </div>

                        <!-- Información detallada -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2">
                                    <i class="fas fa-info-circle"></i> Información General
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong>Puntos requeridos:</strong>
                                        <span class="badge bg-warning text-dark fs-6">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="display: inline;">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            {{ number_format($reward->points_required) }}
                                        </span>
                                    </li>
                                    <li class="mb-2"><strong>Categoría:</strong> {{ ucfirst($reward->category) }}</li>
                                    <li class="mb-2"><strong>Stock disponible:</strong> {{ $reward->stock }} unidades</li>
                                    @if($reward->expiration_date)
                                        <li class="mb-2">
                                            <strong>Válido hasta:</strong> 
                                            {{ \Carbon\Carbon::parse($reward->expiration_date)->format('d/m/Y') }}
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2">
                                    <i class="fas fa-user-check"></i> Tu Estado
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong>Tus puntos actuales:</strong> 
                                        <span class="badge bg-primary fs-6">{{ number_format($user->points) }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Estado:</strong>
                                        @if($user->points >= $reward->points_required)
                                            <span class="badge bg-success">Puedes canjearlo</span>
                                        @else
                                            <span class="badge bg-danger">Puntos insuficientes</span>
                                        @endif
                                    </li>
                                    @if($user->points < $reward->points_required)
                                        <li class="mb-2">
                                            <strong>Te faltan:</strong> 
                                            <span class="text-danger fw-bold">
                                                {{ number_format($reward->points_required - $user->points) }} puntos
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Términos y condiciones -->
                        @if($reward->terms_conditions)
                        <div class="mb-3">
                            <h6 class="border-bottom pb-2">
                                <i class="fas fa-file-contract"></i> Términos y Condiciones
                            </h6>
                            <div class="alert alert-light">
                                <small class="text-muted">{{ $reward->terms_conditions }}</small>
                            </div>
                        </div>
                        @endif

                        <!-- Alerta de disponibilidad -->
                        @if($reward->stock <= 5 && $reward->stock > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>¡Últimas unidades disponibles!</strong> Solo quedan {{ $reward->stock }} en stock.
                        </div>
                        @elseif($reward->stock == 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i>
                            <strong>Agotado</strong> - Esta recompensa no está disponible actualmente.
                        </div>
                        @endif
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                        @if($reward->stock > 0 && $reward->active)
                            @if($user->points >= $reward->points_required)
                                <form action="{{ route('usuario.recompensas.canjear', $reward->reward_id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('¿Estás seguro de canjear esta recompensa por {{ number_format($reward->points_required) }} puntos?')">
                                        <i class="fas fa-shopping-cart"></i> Canjear Ahora
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="fas fa-lock"></i> Puntos Insuficientes
                                </button>
                            @endif
                        @else
                            <button type="button" class="btn btn-secondary" disabled>
                                <i class="fas fa-ban"></i> No Disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                <h5><strong>No se encontraron recompensas</strong></h5>
                <p>No hay recompensas disponibles con los filtros seleccionados.</p>
                <a href="{{ route('usuario.recompensas.catalogo') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-redo"></i> Ver todo el catálogo
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($rewards->hasPages())
    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-center">
            <div class="pagination-wrapper">
                {{ $rewards->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .cuadricula_contenido {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .seccion_artista {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .seccion_artista:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .contenedor_albumes {
        position: relative;
        overflow: hidden;
    }

    .animacion_carta {
        position: relative;
        overflow: hidden;
    }

    .animacion_carta img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .animacion_carta:hover img {
        transform: scale(1.1);
    }

    .efecto_imagen {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .animacion_carta:hover .efecto_imagen {
        opacity: 1;
    }

    .efecto_imagen svg {
        color: white;
        filter: drop-shadow(0 0 10px rgba(255,255,255,0.5));
    }

    .marca_album {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }

    .marca_formato {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
    }

    @media (max-width: 768px) {
        .cuadricula_contenido {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .cuadricula_contenido {
            grid-template-columns: 1fr;
        }
    }


    /* Estilos Gubernamentales para Catálogo de Recompensas - Estilo gob.mx */

/* Tipografía */
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

/* Header */
.row.mb-4 h1 {
    color: #6B1C3D;
    font-weight: 700;
    font-size: 2rem;
}

.text-primary {
    color: #6B1C3D !important;
}

.text-muted {
    color: #6c757d !important;
}

/* Card de puntos del usuario */
.card.bg-primary {
    background: linear-gradient(135deg, #6B1C3D 0%, #8B2450 100%) !important;
    border: none;
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.3);
    transition: transform 0.3s ease;
}

.card.bg-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(107, 28, 61, 0.4);
}

.card.bg-primary .card-body {
    padding: 1rem 1.5rem;
}

.card.bg-primary h4 {
    font-size: 1.75rem;
    font-weight: 800;
}

.card.bg-primary svg {
    vertical-align: middle;
    margin-right: 0.25rem;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

/* Card de filtros */
.card.shadow-sm {
    box-shadow: 0 2px 8px rgba(107, 28, 61, 0.1) !important;
    border: none;
    border-radius: 0.5rem;
}

.card-body {
    padding: 1.5rem;
}

/* Labels de formulario */
.form-label {
    color: #6B1C3D;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

/* Selects personalizados */
.form-select {
    border: 2px solid #e0e0e0;
    border-radius: 0.375rem;
    padding: 0.65rem 1rem;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s ease;
    background-color: white;
}

.form-select:focus {
    border-color: #6B1C3D;
    box-shadow: 0 0 0 0.2rem rgba(107, 28, 61, 0.15);
    outline: none;
}

.form-select:hover {
    border-color: #8B2450;
}

/* Botones */
.btn-outline-secondary {
    color: #6c757d !important;
    border-color: #6c757d !important;
    font-weight: 600;
    border-width: 2px;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background-color: #6c757d !important;
    color: white !important;
    transform: translateY(-2px);
}

.btn-primary {
    background-color: #6B1C3D !important;
    border-color: #6B1C3D !important;
    color: white !important;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #8B2450 !important;
    border-color: #8B2450 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.3);
}

.btn-secondary {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

.btn-secondary:hover {
    background-color: #5a6268 !important;
}

/* Grid de recompensas */
.cuadricula_contenido {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 2rem;
}

/* Tarjetas de recompensas */
.seccion_artista {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 2px 12px rgba(107, 28, 61, 0.12);
    transition: all 0.4s ease;
    overflow: hidden;
    border: 1px solid rgba(107, 28, 61, 0.1);
}

.seccion_artista:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 28px rgba(107, 28, 61, 0.25);
    border-color: #6B1C3D;
}

.contenedor_albumes {
    position: relative;
    overflow: hidden;
}

.animacion_carta {
    position: relative;
    overflow: hidden;
}

.animacion_carta img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.animacion_carta:hover img {
    transform: scale(1.15);
}

/* Efecto overlay en hover */
.efecto_imagen {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(107, 28, 61, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.animacion_carta:hover .efecto_imagen {
    opacity: 1;
}

.efecto_imagen svg {
    color: white;
    filter: drop-shadow(0 0 15px rgba(255,255,255,0.6));
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Badges en las tarjetas */
.marca_album {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 10;
}

.marca_formato {
    position: absolute;
    top: 50px; /* Separado del de arriba */
    right: 12px; /* Ambos del mismo lado */
    z-index: 10;
}

.badge {
    padding: 0.5rem 0.9rem;
    font-size: 0.85rem;
    font-weight: 700;
    border-radius: 0.375rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-dark {
    background-color: #6B1C3D !important;
}

.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-primary {
    background-color: #6B1C3D !important;
}

.badge.bg-secondary {
    background-color: #6B1C3D !important;
}

.badge.bg-light {
    background-color: white !important;
    color: #6B1C3D !important;
}

/* Contenido de las tarjetas */
.seccion_artista > div[style*="margin:10px"] {
    padding: 0 1rem;
    margin: 1rem 0 !important;
}

.seccion_artista span[style*="font-weight: bold"] {
    color: #212529;
    font-weight: 700;
    font-size: 1.1rem;
    display: block;
    line-height: 1.4;
    min-height: 2.8em;
}

/* Información de puntos y stock */
.seccion_artista div[style*="font-size: 0.9rem"] {
    color: #6c757d !important;
    font-size: 0.875rem !important;
    line-height: 1.8;
}

.seccion_artista div[style*="font-size: 0.9rem"] svg {
    color: #6B1C3D !important;
}

.text-warning svg {
    color: #6B1C3D !important;
}

/* Botón de ver detalles */
.seccion_artista .btn-sm {
    padding: 0.6rem 1.25rem;
    font-size: 0.9rem;
    font-weight: 700;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

/* Placeholders de imágenes */
.reward-placeholder {
    background: linear-gradient(135deg, #6B1C3D 0%, #9B3558 100%) !important;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.2);
}

.reward-placeholder-modal {
    background: linear-gradient(135deg, #6B1C3D 0%, #9B3558 100%) !important;
}

/* Modales */
.modal-header.bg-primary {
    background: linear-gradient(135deg, #6B1C3D 0%, #8B2450 100%) !important;
    border: none;
    padding: 1.25rem 1.5rem;
}

.modal-header h5 {
    font-weight: 700;
    font-size: 1.35rem;
}

.modal-body {
    padding: 2rem;
}

.modal-body h6 {
    color: #6B1C3D;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.modal-body h6.border-bottom {
    border-bottom: 2px solid #6B1C3D !important;
    padding-bottom: 0.75rem;
}

.modal-body .img-fluid {
    border: 3px solid #f8f9fa;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.modal-body .img-fluid:hover {
    transform: scale(1.03);
}

.modal-body .list-unstyled li {
    padding: 0.5rem 0;
    line-height: 1.8;
}

.modal-body .list-unstyled strong {
    color: #6B1C3D;
    font-weight: 700;
}

/* Alertas en modales */
.alert {
    border-radius: 0.5rem;
    border: none;
    padding: 1.25rem;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-light {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.alert i {
    margin-right: 0.5rem;
    font-size: 1.2rem;
}

/* Modal footer */
.modal-footer {
    padding: 1.25rem 1.5rem;
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.modal-footer .btn {
    padding: 0.6rem 1.5rem;
    font-weight: 700;
}

/* Estado vacío */
.alert.text-center.py-5 {
    background-color: #fff8e1;
    border: 2px dashed #6B1C3D;
    border-radius: 0.75rem;
    padding: 3rem !important;
}

.alert.text-center.py-5 i {
    color: #6B1C3D !important;
    opacity: 0.6;
}

.alert.text-center.py-5 h5 {
    color: #6B1C3D;
    font-weight: 700;
    margin-top: 1rem;
}

/* Paginación */
.pagination-wrapper {
    margin-top: 2rem;
}

.pagination {
    gap: 0.5rem;
}

.pagination .page-link {
    color: #6B1C3D;
    border: 2px solid #e0e0e0;
    padding: 0.6rem 1rem;
    font-weight: 600;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: #6B1C3D;
    border-color: #6B1C3D;
    color: white;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background-color: #6B1C3D;
    border-color: #6B1C3D;
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.3);
}

.pagination .page-item.disabled .page-link {
    border-color: #e0e0e0;
}

/* Iconos */
.fas, .far {
    margin-right: 0.375rem;
}

/* Responsive */
@media (max-width: 992px) {
    .cuadricula_contenido {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .row.mb-4 h1 {
        font-size: 1.5rem;
    }
    
    .col-md-4.text-md-end {
        margin-top: 1rem;
    }
    
    .card.bg-primary {
        margin-top: 1rem;
    }
    
    .cuadricula_contenido {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
    
    .animacion_carta img {
        height: 220px;
    }
}

@media (max-width: 480px) {
    .cuadricula_contenido {
        grid-template-columns: 1fr;
    }
    
    .marca_formato {
        top: 50px;
        right: 12px;
    }
}

/* Animaciones suaves */
* {
    transition: color 0.2s ease, background-color 0.2s ease;
}

/* Mejoras de accesibilidad */
button:focus,
a:focus,
.form-select:focus {
    outline: 2px solid #6B1C3D;
    outline-offset: 2px;
}

/* Truncate text */
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Hover states */
.btn:hover,
button:hover {
    cursor: pointer;
}

/* Sombras consistentes */
.shadow {
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.15) !important;
}

/* Bordes redondeados */
.rounded {
    border-radius: 0.5rem !important;
}

/* Espaciado */
.mb-4 {
    margin-bottom: 1.5rem !important;
}

/* Links */
a {
    text-decoration: none;
    transition: all 0.2s ease;
}

/* Mejorar contraste */
p {
    line-height: 1.7;
}

/* Botón deshabilitado */
.btn:disabled,
.btn.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Badges con iconos */
.badge svg,
.badge i {
    vertical-align: middle;
    margin-right: 0.25rem;
}
</style>
@endsection