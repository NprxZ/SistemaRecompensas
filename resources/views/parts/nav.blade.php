<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Recompensas</title>
</head>
<body>
    
<div class="row">
    <div class="col-sm">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark navegacion bg-body-tertiary" style="max-width: 100%;" id="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="letra_logo">
                <span class="tsukitones_diseno_principal">gob.mx</span>ystem
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(!Session::has('admin_id') && !Session::has('user_id'))
                <!-- Inicio - Siempre visible -->
                <li style="padding:20%;" class="nav-item navegacion_item">
                    <a class="nav-link active" aria-current="page" href="{{ url('/') }}">
                        <span class="navegacion_item_color">Inicio</span>
                    </a>
                </li>
                @endif
                
                @if(Session::has('admin_id'))
                    <!-- Menú de Administrador -->
                    <li class="nav-item navegacion_item">
                        <a class="nav-link" href="{{ url('/admin/dashboard') }}">
                            <span class="navegacion_item_color">Panel</span>
                        </a>
                    </li>

                    @if(Session::get('user_role') === 'super_admin' || Session::get('user_role') === 'admin')
                        <!-- Opciones exclusivas de administrador -->
                        <li class="nav-item navegacion_item">
                            <a class="nav-link" href="{{ url('/admin/usuarios') }}">
                                <span class="navegacion_item_color">Usuarios</span>
                            </a>
                        </li>

                        <li class="nav-item navegacion_item">
                            <a class="nav-link" href="{{ url('/admin/canjes') }}">
                                <span class="navegacion_item_color">Canjes</span>
                            </a>
                        </li>

                        <li class="nav-item navegacion_item">
                            <a class="nav-link" href="{{ url('/admin/reportes') }}">
                                <span class="navegacion_item_color">Reportes</span>
                            </a>
                        </li>

                        <li class="nav-item navegacion_item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="navegacion_item_color">Catálogos</span>
                            </a>
                            <ul class="dropdown-menu variante">
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/admin/recompensas') }}">
                                    <span class="navegacion_item_color">Recompensas</span>
                                </a></li>
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/admin/actividades') }}">Actividades</a></li>
                                @if(Session::get('user_role') === 'super_admin')
                                    <li><a class="dropdown-item variante_opciones" href="{{ url('/admin/administradores') }}">Administradores</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                
                @elseif(Session::has('user_id'))

                    <!-- Menú visible solo para usuarios autenticados -->
                    <li class="nav-item navegacion_item">
                        <a class="nav-link" href="{{ url('/usuario/dashboard') }}">
                            <span class="navegacion_item_color">Panel</span>
                        </a>
                    </li>

                    <li class="nav-item navegacion_item">
                        <a class="nav-link" href="{{ url('/usuario/recompensas') }}">
                            <span class="navegacion_item_color">Recompensas</span>
                        </a>
                    </li>

                    <!--
                    <li class="nav-item navegacion_item">
                        <a class="nav-link" href="{{ url('/usuario/mis-canjes') }}">
                            <span class="navegacion_item_color">Canjes</span>
                        </a>
                    </li>
                    -->

                
                @else
                    <!-- Usuario NO autenticado - Sin opciones, solo Inicio -->
                @endif
            </ul>

            <div class="nav-contenedor-botones">
                
                @if(Session::has('admin_id'))
                    <!-- Usuario autenticado - ADMINISTRADOR -->
                    <div class="dropdown">
                        <a href="#" class="nav-boton-formato dropdown-toggle" id="userDropdownAdmin" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div><i class="fa-solid fa-user-shield" style="color:white;"></i></div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end variante" aria-labelledby="userDropdownAdmin">
                            <li class="px-3 py-2">
                                <small style="color:white !important;" class="text-muted">{{ Session::get('admin_name') }}</small><br>
                                <small style="color:white !important;" class="text-muted">{{ Session::get('admin_email') }}</small><br>
                                <small  style="color:white !important;" class="text-muted">
                                    <strong>Rol:</strong> {{ ucfirst(Session::get('user_role', 'admin')) }}
                                </small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('auth.admin.logout') }}" method="POST" id="logoutFormAdmin">
                                    @csrf
                                    <button type="submit" class="dropdown-item variante_opciones">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @elseif(Session::has('user_id'))
                    <!-- Usuario autenticado - CONDUCTOR -->
                    <div class="dropdown">
                        <a href="#" class="nav-boton-formato dropdown-toggle" id="userDropdownUser" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div><i class="fa-solid fa-user-check" style="color:white;"></i></div>
                        </a>
                        <style>
                         #userDropdownUser::after {
                            display: none !important;
                             }
                        </style>
                        <ul class="dropdown-menu dropdown-menu-end variante" aria-labelledby="userDropdownUser" style="background-color: rgba(97, 18, 50) !important;">
                            <li class="px-3 py-2">
                                <small style="color:white !important;" class="text-muted">{{ Session::get('user_name') }}</small><br>
                                <small style="color:white !important;" class="text-muted">{{ Session::get('user_email') }}</small><br>
                                <small style="color:white !important;" class="text-muted">
                                    <strong>Placa:</strong> {{ Session::get('user_plate') }}
                                </small><br>
                                <small style="color:white !important;" class="text-muted">
                                    <strong>Puntos:</strong> {{ Session::get('user_points', 0) }}
                                </small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="{{ url('/usuario/dashboard') }}" class="dropdown-item variante_opciones">
                                    <i class="fa-solid fa-user-cog me-2"></i>Mi Perfil
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('auth.usuario.logout') }}" method="POST" id="logoutFormUser">
                                    @csrf
                                    <button type="submit" class="dropdown-item variante_opciones">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Botón de login para usuarios no autenticados -->
                    <a href="#" class="nav-boton-formato boton-de-login" 
                       data-bs-toggle="modal" data-bs-target="#modalLogin">
                        <div><i class="fa-solid fa-user" style="color:white;"></i></div>
                    </a>
                @endif
            </div>

            <!-- Buscador -->
            <form class="d-flex" role="search" method="get" action="/buscar">
                <div class="input-buscar-container">
                    <i class="fas fa-search"></i>
                    <input maxlength="40" 
                           style="background-color: rgba(104, 32, 61, 1); border-radius: 5rem !important; border-color:#9295af; border-width: 0.1rem; color:#7f829f;" 
                           class="form-control me-2 input-grueso" 
                           type="search" 
                           placeholder="Buscar recompensa.." 
                           aria-label="Search" 
                           name="recompensa_buscar" />
                    <div class="boton-filtro">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15px" fill="none" viewBox="0 0 24 24" 
                                 stroke-width="1.5" stroke="#a7acd1" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                            </svg>
                            <span>Filtros</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Modal de Login - Solo se muestra si NO hay sesión -->
@if(!Session::has('admin_id') && !Session::has('user_id'))
<div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cuadro-login">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalTitle">Acceso al Sistema</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Tabs para Usuario / Administrador -->
                <ul class="nav nav-tabs mb-3" id="loginTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="usuario-tab" data-bs-toggle="tab" 
                                data-bs-target="#usuario-login" type="button" role="tab">
                            Usuario
                        </button>
                    </li>

                </ul>

                <div class="tab-content" id="loginTabContent">
                    
                    <!-- Login Usuario -->
                    <div class="tab-pane fade show active" id="usuario-login" role="tabpanel">
                        <form action="{{ route('auth.usuario.login') }}" method="POST" id="loginFormUsuario">
                            @csrf
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label label-modal">Placa del Vehículo</label>
                                <input type="text" name="plate_number" class="form-control input-modal" 
                                       placeholder="ABC-1234" value="{{ old('plate_number') }}" maxlength="7" required>
                                <small class="text-muted">Ingresa tu placa tal como la registraste</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label label-modal">Contraseña</label>
                                <input type="password" name="password" class="form-control input-modal" 
                                       placeholder="••••••••" maxlength="8" required>
                            </div>
                            
                            <button type="submit" class="btn btn-login-modal w-100 mb-3">
                                Iniciar sesión como Usuario
                            </button>


                        </form>

                        <!-- Formulario de Registro Usuario (oculto inicialmente) -->
                        <div id="registerFormUsuario" style="display: none;">
                            <button class="btn btn-link mb-3 p-0" id="backToLoginUsuario">
                                <i class="fa-solid fa-arrow-left me-2"></i>Volver al login
                            </button>
                            
                            <form action="{{ route('auth.usuario.register') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Nombre</label>
                                        <input type="text" name="first_name" class="form-control input-modal" 
                                               placeholder="Juan" maxlength="50" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Apellido</label>
                                        <input type="text" name="last_name" class="form-control input-modal" 
                                               placeholder="Pérez" maxlength="50" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label label-modal">Correo electrónico</label>
                                    <input type="email" name="email" class="form-control input-modal" 
                                           placeholder="correo@ejemplo.com" maxlength="100" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label label-modal">Placa del Vehículo</label>
                                    <input type="text" name="plate_number" class="form-control input-modal" 
                                           placeholder="ABC-1234" maxlength="20" required>
                                    <small class="text-muted">Esta será tu usuario para iniciar sesión</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label label-modal">Teléfono</label>
                                    <input type="tel" name="phone" class="form-control input-modal" 
                                           placeholder="555-1234-5678" maxlength="20">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Marca del Vehículo</label>
                                        <input type="text" name="vehicle_brand" class="form-control input-modal" 
                                               placeholder="Toyota" maxlength="50" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Modelo</label>
                                        <input type="text" name="vehicle_model" class="form-control input-modal" 
                                               placeholder="Corolla" maxlength="50" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label label-modal">Año del Vehículo</label>
                                    <input type="number" name="vehicle_year" class="form-control input-modal" 
                                           placeholder="2020" min="1900" max="{{ date('Y') + 1 }}" required>
                                </div>
                                
                                <div class="alert alert-info">
                                    <small>Tu contraseña será enviada a tu correo electrónico después del registro.</small>
                                </div>
                                
                                <button type="submit" class="btn btn-login-modal w-100">
                                    Crear mi cuenta
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Login Administrador -->
                    <div class="tab-pane fade" id="admin-login" role="tabpanel">
                        <form action="{{ route('auth.admin.login') }}" method="POST" id="loginFormAdmin">
                            @csrf
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label label-modal">Correo electrónico</label>
                                <input type="email" name="email" class="form-control input-modal" 
                                       placeholder="admin@rewards.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label label-modal">Contraseña</label>
                                <input type="password" name="password" class="form-control input-modal" 
                                       placeholder="••••••" required>
                            </div>
                            
                            <button type="submit" class="btn btn-login-modal w-100 mb-3">
                                Iniciar sesión como Administrador
                            </button>

                            <div class="text-center register-link">
                                <small class="text-muted">Acceso solo para personal autorizado</small>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle entre login y registro de usuario
document.addEventListener('DOMContentLoaded', function() {
    const showRegisterBtn = document.getElementById('showRegisterUsuario');
    const backToLoginBtn = document.getElementById('backToLoginUsuario');
    const loginFormUsuario = document.getElementById('loginFormUsuario');
    const registerFormUsuario = document.getElementById('registerFormUsuario');
    
    if (showRegisterBtn) {
        showRegisterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginFormUsuario.style.display = 'none';
            registerFormUsuario.style.display = 'block';
            document.getElementById('modalTitle').textContent = 'Registro de Usuario';
        });
    }
    
    if (backToLoginBtn) {
        backToLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            registerFormUsuario.style.display = 'none';
            loginFormUsuario.style.display = 'block';
            document.getElementById('modalTitle').textContent = 'Acceso al Sistema';
        });
    }
});

// Si hay errores de validación o se solicita mostrar modal, reabrirlo
@if($errors->any() || session('show_modal'))
    window.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalLogin'));
        modal.show();
    });
@endif
</script>
@endif

    </div>
</div>
</body>
</html>