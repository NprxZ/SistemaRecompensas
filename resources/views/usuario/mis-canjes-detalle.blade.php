@extends('layouts.app')

@section('title', 'Detalle del Canje')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-file-invoice text-primary"></i> Detalle del Canje
            </h1>
            <p class="text-muted">Información completa de tu canje #{{ $redemption->canje_id }}</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('usuario.mis-canjes') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Mis Canjes
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información del Canje -->
        <div class="col-lg-8">
            <!-- Estado del Canje -->
            <div class="card shadow-sm mb-4">
                <div class="card-header 
                    @if($redemption->estado == 'pendiente') bg-warning
                    @elseif($redemption->estado == 'aprobado') bg-success
                    @elseif($redemption->estado == 'entregado') bg-info
                    @else bg-danger
                    @endif text-white">
                    <h5 class="mb-0">
                        @if($redemption->estado == 'pendiente')
                            ⏳ Estado: Pendiente de Aprobación
                        @elseif($redemption->estado == 'aprobado')
                            ✅ Estado: Aprobado - Listo para Recoger
                        @elseif($redemption->estado == 'entregado')
                            📦 Estado: Entregado
                        @else
                            ❌ Estado: Cancelado
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($redemption->estado == 'pendiente')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i>
                            <strong>Tu canje está en revisión.</strong> 
                            Nuestro equipo está procesando tu solicitud. Te notificaremos cuando esté listo.
                        </div>
                    @elseif($redemption->estado == 'aprobado')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>¡Tu canje ha sido aprobado!</strong> 
                            Ya puedes recoger tu recompensa presentando el código de canje.
                            @if($redemption->aprobado_por)
                                <br><small>Aprobado el: {{ \Carbon\Carbon::parse($redemption->aprobado_por)->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    @elseif($redemption->estado == 'entregado')
                        <div class="alert alert-info">
                            <i class="fas fa-box-open"></i>
                            <strong>¡Recompensa entregada!</strong> 
                            Esperamos que disfrutes tu recompensa.
                            @if($redemption->fecha_entrega)
                                <br><small>Entregado el: {{ \Carbon\Carbon::parse($redemption->fecha_entrega)->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i>
                            <strong>Este canje ha sido cancelado.</strong>
                            @if($redemption->notas)
                                <br>Motivo: {{ $redemption->notas }}
                            @endif
                        </div>
                    @endif

                </div>
            </div>

            <!-- Información de la Recompensa -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-gift"></i> Información de la Recompensa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            @if($redemption->imagen)
                                <img src="{{ asset('images/' . $redemption->imagen) }}" 
                                     alt="{{ $redemption->titulo }}" 
                                     class="img-fluid rounded shadow"
                                     style="max-height: 200px;">
                            @else
                                <div class="reward-placeholder-large mx-auto" style="width: 200px; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; border-radius: 0.5rem;">
                                    <svg width="80" height="80" fill="white" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-3">{{ $redemption->titulo }}</h4>
                            
                            <div class="mb-3">
                                <span class="badge bg-secondary">{{ ucfirst($redemption->categoria) }}</span>
                            </div>

                            <p class="text-muted">{{ $redemption->descripcion }}</p>

                            <hr>

                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2">
                                        <strong>Puntos usados:</strong>
                                        <br>
                                        <span class="text-primary fs-5">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="display: inline;">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            {{ number_format($redemption->puntos_utilizados) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2">
                                        <strong>Fecha de canje:</strong>
                                        <br>
                                        {{ \Carbon\Carbon::parse($redemption->fecha_creacion)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            @if($redemption->terminos_condiciones)
                            <div class="mt-3">
                                <h6 class="text-muted small">Términos y Condiciones:</h6>
                                <p class="small text-muted">{{ $redemption->terminos_condiciones }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Código de Canje y Acciones -->
        <div class="col-lg-4">
            <!-- Código de Canje -->
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-barcode"></i> Código de Canje
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="code-display p-4 bg-light rounded border border-primary">
                            <h2 class="mb-0 font-monospace text-primary fw-bold">
                                {{ $redemption->codigo_canje }}
                            </h2>
                        </div>
                    </div>

<!-- Código QR -->
@php
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;

try {
    $builder = new Builder(
        writer: new SvgWriter(),
        data: $redemption->codigo_canje,
        size: 200,
        margin: 10
    );
    
    $result = $builder->build();
    $qrCodeSvg = $result->getString();
} catch (\Exception $e) {
    $qrCodeSvg = null;
    \Log::error('Error generando QR: ' . $e->getMessage());
}
@endphp

@if($qrCodeSvg)
<div class="qr-code-container mt-3 mb-3">
    {!! $qrCodeSvg !!}
</div>
@endif

<p class="text-muted small mb-3">
    Presenta este código o escanea el QR para reclamar tu recompensa
</p>

<div class="d-grid gap-2">
    <button class="btn btn-outline-primary btn-sm" onclick="copyCode('{{ $redemption->codigo_canje }}')">
        <i class="fas fa-copy"></i> Copiar Código
    </button>
    
    <a href="{{ route('usuario.canje.pdf', $redemption->canje_id) }}" 
       class="btn btn-primary btn-sm">
        <i class="fas fa-file-pdf"></i> Descargar Comprobante PDF
    </a>
    
    <a href="{{ route('usuario.canje.email', $redemption->canje_id) }}" 
       class="btn btn-success btn-sm">
        <i class="fas fa-envelope"></i> Enviar por Correo
    </a>
</div>
                    

                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información del Canje
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <strong>Fecha de solicitud:</strong>
                            <br>
                            <span class="text-muted">
                                {{ \Carbon\Carbon::parse($redemption->fecha_creacion)->format('d/m/Y H:i:s') }}
                            </span>
                        </li>
                        <li class="mb-3">
                            <strong>Estado actual:</strong>
                            <br>
                            @if($redemption->estado == 'pendiente')
                                <span class="badge bg-warning text-dark">⏳ Pendiente</span>
                            @elseif($redemption->estado == 'aprobado')
                                <span class="badge bg-success">✅ Aprobado</span>
                            @elseif($redemption->estado == 'entregado')
                                <span class="badge bg-info">📦 Entregado</span>
                            @else
                                <span class="badge bg-danger">❌ Cancelado</span>
                            @endif
                        </li>
                        @if($redemption->notas)
                        <li class="mb-0">
                            <strong>Notas:</strong>
                            <br>
                            <span class="text-muted small">{{ $redemption->notas }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .code-display {
        letter-spacing: 0.2em;
    }

    /* Timeline styles */
    .timeline {
        position: relative;
        padding-left: 50px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 30px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -38px;
        top: 20px;
        bottom: -10px;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-marker {
        position: absolute;
        left: -47px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #dee2e6;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-item.completed .timeline-marker {
        background: #28a745;
        box-shadow: 0 0 0 2px #28a745;
    }

    .timeline-item.completed::before {
        background: #28a745;
    }

    .timeline-content h6 {
        font-weight: 600;
        color: #495057;
    }

    .timeline-item.completed .timeline-content h6 {
        color: #28a745;
    }

    /* Estilos Gubernamentales para Detalle del Canje - Estilo gob.mx */

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

/* Botones */
.btn-outline-secondary {
    color: #6c757d !important;
    border-color: #6c757d !important;
    font-weight: 600;
    padding: 0.5rem 1.2rem;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    color: white !important;
    transform: translateY(-2px);
}

.btn-outline-primary {
    color: #6B1C3D !important;
    border-color: #6B1C3D !important;
    font-weight: 600;
}

.btn-outline-primary:hover {
    background-color: #6B1C3D !important;
    color: white !important;
}

.btn-outline-info {
    color: #6B1C3D !important;
    border-color: #6B1C3D !important;
}

.btn-outline-info:hover {
    background-color: #6B1C3D !important;
    color: white !important;
}

/* Cards */
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

/* Card Headers con colores gubernamentales */
.card-header {
    padding: 1rem 1.5rem;
    font-weight: 600;
    border-bottom: none;
}

.card-header.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.card-header.bg-success {
    background-color: #28a745 !important;
    color: white !important;
}

.card-header.bg-info {
    background-color: #6B1C3D !important;
    color: white !important;
}

.card-header.bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
}

.card-header.bg-primary {
    background-color: #6B1C3D !important;
    color: white !important;
}

.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 3px solid #6B1C3D !important;
    color: #6B1C3D !important;
}

.card-header h5,
.card-header h6 {
    color: inherit !important;
}

/* Alertas personalizadas */
.alert {
    border-radius: 0.5rem;
    border: none;
    padding: 1.25rem;
    font-size: 0.95rem;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert i {
    font-size: 1.2rem;
    vertical-align: middle;
    margin-right: 0.5rem;
}

.alert strong {
    font-weight: 700;
}

/* Timeline personalizado */
.timeline {
    position: relative;
    padding-left: 50px;
    margin-top: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 35px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -38px;
    top: 20px;
    bottom: -10px;
    width: 3px;
    background: #e9ecef;
    border-radius: 2px;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -47px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #e9ecef;
    border: 4px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
    z-index: 2;
}

.timeline-item.completed .timeline-marker {
    background: #6B1C3D;
    box-shadow: 0 0 0 3px #6B1C3D;
}

.timeline-item.completed::before {
    background: #6B1C3D;
}

.timeline-content h6 {
    font-weight: 700;
    color: #495057;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.timeline-item.completed .timeline-content h6 {
    color: #6B1C3D;
}

.timeline-content small {
    color: #6c757d;
    font-size: 0.875rem;
}

/* Imagen de recompensa */
.img-fluid.rounded {
    border: 3px solid #f8f9fa;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.img-fluid.rounded:hover {
    transform: scale(1.05);
}

/* Placeholder de imagen */
.reward-placeholder-large {
    background: linear-gradient(135deg, #6B1C3D 0%, #9B3558 100%) !important;
    box-shadow: 0 4px 16px rgba(107, 28, 61, 0.3);
}

/* Información de la recompensa */
.card-body h4 {
    color: #212529;
    font-weight: 700;
    font-size: 1.75rem;
    margin-bottom: 1rem;
}

/* Badges */
.badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
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

/* Código de canje destacado */
.card.border-primary {
    border: 2px solid #6B1C3D !important;
}

.code-display {
    letter-spacing: 0.3em;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border: 2px dashed #6B1C3D !important;
    transition: all 0.3s ease;
}

.code-display:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.2);
}

.code-display h2 {
    color: #6B1C3D !important;
    font-family: 'Courier New', monospace;
    font-weight: 800;
    font-size: 1.75rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.font-monospace {
    font-family: 'Courier New', monospace !important;
}

/* Botones personalizados */
.btn-sm {
    padding: 0.5rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.btn-outline-primary.btn-sm {
    border-width: 2px;
}

/* Información adicional */
.list-unstyled li {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.list-unstyled li:last-child {
    border-bottom: none;
}

.list-unstyled strong {
    color: #6B1C3D;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Card de ayuda */
.card.border-info {
    border: 2px solid #6B1C3D !important;
}

.card-header.bg-info {
    background-color: #6B1C3D !important;
}

/* Divisores */
hr {
    border-top: 2px solid #e9ecef;
    margin: 1.5rem 0;
}

/* Textos especiales */
.text-primary.fs-5 {
    color: #6B1C3D !important;
    font-weight: 700;
    font-size: 1.5rem !important;
}

/* Grid de botones */
.d-grid.gap-2 {
    gap: 0.75rem !important;
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
    
    .btn-outline-secondary {
        width: 100%;
    }
    
    .timeline {
        padding-left: 40px;
    }
    
    .timeline-marker {
        left: -40px;
        width: 20px;
        height: 20px;
    }
    
    .timeline-item::before {
        left: -31px;
    }
}

/* Animaciones */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Toast notifications */
.toast.bg-success {
    background-color: #28a745 !important;
}

.toast-header.bg-success {
    background-color: #28a745 !important;
}

/* Iconos */
.fas, .far {
    margin-right: 0.5rem;
}

/* Mejoras visuales */
.card-body p.small {
    line-height: 1.6;
}

.text-muted.small {
    font-size: 0.875rem;
}

/* Sombras consistentes */
.shadow {
    box-shadow: 0 4px 12px rgba(107, 28, 61, 0.15) !important;
}

/* Estados hover */
button:hover,
a.btn:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

/* Bordes redondeados consistentes */
.rounded {
    border-radius: 0.5rem !important;
}

/* Colores de fondo para estados */
.bg-light {
    background-color: #f8f9fa !important;
}

/* Espaciado */
.mb-3 {
    margin-bottom: 1rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

/* Títulos en cards */
.card-header h5,
.card-header h6 {
    font-weight: 700;
    margin-bottom: 0;
}

/* Links */
a {
    text-decoration: none;
    transition: all 0.2s ease;
}

/* Mejorar legibilidad */
p {
    line-height: 1.7;
}

/* Botón copiar código - efecto especial */
.btn-outline-primary:active {
    transform: scale(0.95);
}

/* Información destacada */
.card-body .row .col-6 strong {
    color: #6B1C3D;
    font-size: 0.9rem;
    font-weight: 700;
}

/* Términos y condiciones */
.card-body h6.text-muted {
    color: #6B1C3D !important;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    margin-top: 1rem;
}

.qr-code-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: white;
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.qr-code-container svg {
    max-width: 200px;
    height: auto;
}
</style>

<script>
    function copyCode(code) {
        // Usar la API moderna del portapapeles
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(code).then(function() {
                // Cambiar el botón temporalmente
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-primary');
                
                setTimeout(function() {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            });
        } else {
            // Fallback para navegadores antiguos
            const temp = document.createElement('textarea');
            temp.value = code;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            alert('Código copiado: ' + code);
        }
    }
</script>

<!-- Toast de éxito si viene de un canje recién realizado -->
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

<script>
    setTimeout(function() {
        document.querySelector('.toast').classList.remove('show');
    }, 5000);
</script>
@endif
@endsection