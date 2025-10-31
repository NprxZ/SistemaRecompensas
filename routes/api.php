<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PeliculasApiController;

// Rutas públicas de API (sin autenticación)
Route::prefix('v1')->group(function () {
    
    // Películas
    Route::get('/peliculas', [PeliculasApiController::class, 'obtenerPeliculas']);
    Route::get('/peliculas/{id}', [PeliculasApiController::class, 'obtenerPeliculaPorId']);
    Route::get('/peliculas/categoria/{categoria_id}', [PeliculasApiController::class, 'obtenerPeliculasPorCategoria']);
    
    // Inventario
    Route::get('/inventario', [PeliculasApiController::class, 'obtenerInventario']);
    
    // Rankings
    Route::prefix('rankings')->group(function () {
        Route::get('/peliculas-populares', [PeliculasApiController::class, 'obtenerPeliculasPopulares']);
        Route::get('/mejores-clientes', [PeliculasApiController::class, 'obtenerMejoresClientes']);
        Route::get('/categorias-populares', [PeliculasApiController::class, 'obtenerCategoriasPopulares']);
    });
});
