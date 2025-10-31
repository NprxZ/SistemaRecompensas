@extends('layouts.app')

@section('title', 'Panel de Usuario')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h1 style="font-weight:bold !important;" class="h3 mb-3 text-dark">Bienvenido, {{ $user->first_name }} {{ $user->last_name }}</h1>
                    <p class="text-muted mb-2">
                        <span style="font-weight:bold !important;" class="fw-semibold">Vehículo:</span> {{ $user->vehicle_brand }} {{ $user->vehicle_model }} ({{ $user->vehicle_year }})
                    </p>
                    <p class="text-muted small mb-0">
                        <span style="font-weight:bold !important;" class="fw-semibold">Placa:</span> {{ $user->plate_number }}
                    </p>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas / KPIs -->
    <div class="row g-4 mb-4" style="text-align:center !important; justify-content:center !important;">
        <!-- Puntos Actuales -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Puntos Actuales</p>
                            <h3 class="card-title mb-0 display-5 fw-bold">{{ number_format($user->points) }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placa del Vehículo -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Placa del Vehículo</p>
                            <h3 class="card-title mb-0 display-6 fw-bold">{{ $user->plate_number }}</h3>
                            <p class="small opacity-75 mb-0 mt-1">{{ $user->vehicle_brand }}</p>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-6a1 1 0 00-1-1H3zm7 0a1 1 0 011-1h2.05a2.5 2.5 0 014.9 0H19a1 1 0 011 1v10a1 1 0 01-1 1h-1.05a2.5 2.5 0 01-4.9 0H11a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recompensas Canjeadas -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Canjes Realizados</p>
                            <h3 class="card-title mb-0 display-5 fw-bold">{{ count($recentRedemptions) }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>



    <!-- Catálogo de Recompensas -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="contorno_titulo_cartas mb-3">
                <span style="color:white;">Catálogo de Recompensas Disponibles</span>
            </div>
            
            <!-- Filtros -->
            <form method="GET" action="{{ route('usuario.dashboard') }}" class="row g-3">
                <div class="col-md-4">
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        <option value="descuentos" {{ request('category') == 'descuentos' ? 'selected' : '' }}>Descuentos</option>
                        <option value="servicios" {{ request('category') == 'servicios' ? 'selected' : '' }}>Servicios</option>
                        <option value="productos" {{ request('category') == 'productos' ? 'selected' : '' }}>Productos</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="order" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="points_asc" {{ request('order') == 'points_asc' ? 'selected' : '' }}>Puntos: Menor a Mayor</option>
                        <option value="points_desc" {{ request('order') == 'points_desc' ? 'selected' : '' }}>Puntos: Mayor a Menor</option>
                        <option value="newest" {{ request('order') == 'newest' ? 'selected' : '' }}>Más Recientes</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="available" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        <option value="yes" {{ request('available') == 'yes' ? 'selected' : '' }}>Solo Disponibles</option>
                        <option value="affordable" {{ request('available') == 'affordable' ? 'selected' : '' }}>Que Puedo Canjear</option>
                    </select>
                </div>
            </form>
        </div>
        
        <div class="card-body">
            <div class="cuadricula_contenido">
                @forelse($featuredRewards as $reward)
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
                                             style="max-height: 350px;">
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
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>No se encontraron recompensas</strong> con los filtros seleccionados.
                        <br>
                        <a href="{{ route('usuario.dashboard') }}" class="btn btn-sm btn-primary mt-2">
                            Ver todo el catálogo
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            @if(count($featuredRewards) > 0)
            <div class="text-center mt-4">
                <a href="{{ route('usuario.recompensas.catalogo') }}" class="btn btn-outline-primary">
                    Ver Catálogo Completo
                    <svg width="16" height="16" fill="currentColor" class="ms-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .reward-card {
        transition: all 0.3s ease;
    }
    
    .reward-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Estilos del catálogo (inspirados en el diseño compartido) */
    .contorno_titulo_cartas {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        padding: 10px 0;
        border-bottom: 3px solid #667eea;
    }

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


    /* Colores Gubernamentales - Estilo gob.mx */

/* KPIs / Tarjetas de Estadísticas */
.bg-primary {
    background-color: #6B1C3D !important; /* Vino guinda principal */
}

.bg-success {
    background-color: #8B2450 !important; /* Vino más claro */
}

.bg-warning {
    background-color: #9B3558 !important; /* Tono intermedio */
    color: white !important;
}

.bg-info {
    background-color: #AB4660 !important; /* Tono más suave */
}

/* Botones */
.btn-primary {
    background-color: #6B1C3D !important;
    border-color: #6B1C3D !important;
    color: white !important;
}

.btn-primary:hover {
    background-color: #8B2450 !important;
    border-color: #8B2450 !important;
}

.btn-outline-primary {
    color: #6B1C3D !important;
    border-color: #6B1C3D !important;
}

.btn-outline-primary:hover {
    background-color: #6B1C3D !important;
    color: white !important;
}

/* Headers de Cards */
.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 3px solid #6B1C3D !important;
}

/* Badges */
.badge.bg-primary {
    background-color: #6B1C3D !important;
}

.badge.bg-success {
    background-color: #28a745 !important; /* Verde para disponible */
}

.badge.bg-warning {
    background-color: #ffc107 !important; /* Amarillo para advertencias */
    color: #212529 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important; /* Rojo para agotado */
}

.badge.bg-dark {
    background-color: #6B1C3D !important; /* Categorías en vino */
}

/* Modal Headers */
.modal-header.bg-primary {
    background-color: #6B1C3D !important;
}

/* Título de sección */
.contorno_titulo_cartas {
    font-size: 1.5rem;
    font-weight: bold;
    color: #6B1C3D !important;
    padding: 10px 0;
    border-bottom: 3px solid #6B1C3D !important;
}

/* Links y enlaces */
a {
    color: #6B1C3D !important;
}

a:hover {
    color: #8B2450 !important;
}

/* Bordes de tarjetas en hover */
.seccion_artista:hover {
    border: 2px solid #6B1C3D;
}

/* Iconos con color gubernamental */
.text-warning svg,
.text-warning i {
    color: #6B1C3D !important;
}

/* Badges de estado en tarjetas */
.marca_album .badge {
    background-color: #6B1C3D !important;
    color: white !important;
}

/* Gradiente gubernamental para placeholders */
.reward-placeholder,
.reward-placeholder-modal {
    background: linear-gradient(135deg, #6B1C3D 0%, #9B3558 100%) !important;
}

/* Bordes inferiores en modales */
.modal-body h6.border-bottom {
    border-bottom-color: #6B1C3D !important;
}

/* Text colors */
.text-primary {
    color: #6B1C3D !important;
}

/* Alertas personalizadas */
.alert-warning {
    background-color: #fff3cd;
    border-color: #6B1C3D;
}

/* Formularios - Select */
.form-select:focus {
    border-color: #6B1C3D;
    box-shadow: 0 0 0 0.25rem rgba(107, 28, 61, 0.25);
}

/* Tablas */
.table-hover tbody tr:hover {
    background-color: rgba(107, 28, 61, 0.05);
}

/* Toast notifications */
.toast.bg-success {
    background-color: #28a745 !important;
}

.toast-header.bg-success {
    background-color: #28a745 !important;
}

/* Headers de tabla */
.table-light {
    background-color: #f8f9fa;
}

.table thead th {
    color: #6B1C3D;
    font-weight: 600;
}

/* Badges en transacciones */
.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
}

.badge.bg-info {
    background-color: #0dcaf0 !important;
}

/* Efecto de hover en imágenes */
.efecto_imagen {
    background: rgba(107, 28, 61, 0.85) !important;
}

/* Botones secundarios */
.btn-secondary {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

.btn-secondary:hover {
    background-color: #5a6268 !important;
}

/* Card shadows con tono gubernamental */
.card.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(107, 28, 61, 0.075) !important;
}

.seccion_artista {
    box-shadow: 0 2px 8px rgba(107, 28, 61, 0.1);
}

.seccion_artista:hover {
    box-shadow: 0 8px 20px rgba(107, 28, 61, 0.2);
}

/* Texto muted con mejor contraste */
.text-muted {
    color: #6c757d !important;
}

/* Headers de card con estilo gob.mx */
.card-header {
    background-color: #f8f9fa;
    border-bottom: 2px solid #6B1C3D;
}

/* Botón de cerrar en modales */
.btn-close:focus {
    box-shadow: 0 0 0 0.25rem rgba(107, 28, 61, 0.25);
}

/* Progress bars si las hay */
.progress-bar {
    background-color: #6B1C3D !important;
}

/* Responsive: mantener colores en mobile */
@media (max-width: 768px) {
    .btn-primary,
    .badge.bg-primary,
    .bg-primary {
        background-color: #6B1C3D !important;
    }
}

/* Hover en badges */
.badge:hover {
    opacity: 0.9;
}

/* Sombras en botones */
.btn-primary:focus,
.btn-outline-primary:focus {
    box-shadow: 0 0 0 0.25rem rgba(107, 28, 61, 0.25) !important;
}

/* Links en el footer del modal */
.modal-footer .btn-primary {
    background-color: #6B1C3D !important;
}

.modal-footer .btn-primary:hover {
    background-color: #8B2450 !important;
}

/* Estados de las tarjetas KPI con mejor contraste */
.card.bg-primary .card-title,
.card.bg-success .card-title,
.card.bg-warning .card-title,
.card.bg-info .card-title {
    color: white !important;
}

.card.bg-primary .card-text,
.card.bg-success .card-text,
.card.bg-warning .card-text,
.card.bg-info .card-text {
    color: rgba(255, 255, 255, 0.9) !important;
}
</style>

<!-- Alertas de éxito/error -->
@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div class="toast show bg-success text-white" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">¡Éxito!</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div class="toast show bg-danger text-white" role="alert">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif

<script>
    // Auto-hide toasts después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var toasts = document.querySelectorAll('.toast');
            toasts.forEach(function(toast) {
                toast.classList.remove('show');
            });
        }, 5000);
    });
</script>
@endsection