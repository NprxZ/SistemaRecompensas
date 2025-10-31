<?php

//RURAS DE LAS SECCIONES DEL SISTEMA DE RECOMPENSAS

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RecompensasController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActividadesAdminController;
use App\Http\Controllers\AdministradoresController;
use App\Http\Controllers\CanjesAdminController;
use App\Http\Controllers\RecompensasAdminController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\UsuariosAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    
    // Administradores
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    // Usuarios (Conductores)
    Route::post('/usuario/login', [AuthController::class, 'loginUsuario'])->name('usuario.login');
    Route::post('/usuario/logout', [AuthController::class, 'logoutUsuario'])->name('usuario.logout');
    Route::post('/usuario/register', [AuthController::class, 'registerUsuario'])->name('usuario.register');
    
    // Recuperación de contraseña
    Route::get('/password/reset', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/password/reset', [AuthController::class, 'sendResetLink'])->name('password.email');
    
    // Verificación de sesión (API)
    Route::get('/check', [AuthController::class, 'check'])->name('check');
});

//RUTA POR DEFECTO

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

//RUTAS DE LOS USUARIOS

Route::middleware(['auth.user'])->prefix('usuario')->name('usuario.')->group(function () {
    
    // Dashboard del usuario
    Route::get('/dashboard', [UsuarioController::class, 'dashboard'])->name('dashboard');
    
    // Perfil del usuario
    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('perfil');
    Route::put('/perfil/update', [UsuarioController::class, 'updatePerfil'])->name('perfil.update');
    Route::post('/perfil/change-password', [UsuarioController::class, 'changePassword'])->name('perfil.change-password');
    
    // Puntos y transacciones
    Route::get('/puntos', [UsuarioController::class, 'puntos'])->name('puntos');
    Route::get('/historial', [UsuarioController::class, 'historial'])->name('historial');
    
    // Recompensas
    Route::get('/recompensas', [RecompensasController::class, 'catalogo'])->name('recompensas.catalogo');
    Route::get('/recompensas/{reward_id}', [RecompensasController::class, 'detalle'])->name('recompensas.detalle');
    Route::post('/recompensas/{reward_id}/canjear', [RecompensasController::class, 'canjear'])->name('recompensas.canjear');
    
    // Mis canjes
    Route::get('/mis-canjes', [RecompensasController::class, 'misCanjes'])->name('mis-canjes');
    Route::get('/mis-canjes/{redemption_id}', [RecompensasController::class, 'detalleCanjes'])->name('mis-canjes.detalle');
});



Route::middleware(['auth.admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard del administrador
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestión de Usuarios
    Route::resource('usuarios', UsuariosAdminController::class);
    Route::post('usuarios/{user_id}/toggle-active', [UsuariosAdminController::class, 'toggleActive'])->name('usuarios.toggle-active');
    Route::post('usuarios/{user_id}/add-points', [UsuariosAdminController::class, 'addPoints'])->name('usuarios.add-points');
    
    // Gestión de Recompensas
    Route::resource('recompensas', RecompensasAdminController::class);
    Route::post('recompensas/{reward_id}/toggle-active', [RecompensasAdminController::class, 'toggleActive'])->name('recompensas.toggle-active');
    
    // Gestión de Canjes
    Route::get('canjes', [CanjesAdminController::class, 'index'])->name('canjes.index');
    Route::get('canjes/{redemption_id}', [CanjesAdminController::class, 'show'])->name('canjes.show');
    Route::post('canjes/{redemption_id}/aprobar', [CanjesAdminController::class, 'aprobar'])->name('canjes.aprobar');
    Route::post('canjes/{redemption_id}/entregar', [CanjesAdminController::class, 'entregar'])->name('canjes.entregar');
    Route::post('canjes/{redemption_id}/cancelar', [CanjesAdminController::class, 'cancelar'])->name('canjes.cancelar');
    
    // Gestión de Actividades
    Route::resource('actividades', ActividadesAdminController::class);
    Route::post('actividades/{activity_id}/toggle-active', [ActividadesAdminController::class, 'toggleActive'])->name('actividades.toggle-active');
    
    // Reportes y Estadísticas
    Route::get('reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('reportes/usuarios', [ReportesController::class, 'usuarios'])->name('reportes.usuarios');
    Route::get('reportes/recompensas', [ReportesController::class, 'recompensas'])->name('reportes.recompensas');
    Route::get('reportes/puntos', [ReportesController::class, 'puntos'])->name('reportes.puntos');
    
    // Gestión de Administradores (solo super_admin)
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('administradores', AdministradoresController::class);
        Route::post('administradores/{admin_id}/toggle-active', [AdministradoresController::class, 'toggleActive'])->name('administradores.toggle-active');
    });
});

Route::get('/usuario/canje/{redemption_id}/pdf', [UsuarioController::class, 'downloadRedemptionPDF'])
    ->name('usuario.canje.pdf');

    Route::get('/usuario/canje/{redemption_id}/email', [UsuarioController::class, 'sendRedemptionEmail'])
    ->name('usuario.canje.email');