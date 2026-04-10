<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;

Route::get('/login', fn () => response()->json([
    'message' => 'Não autenticado.',
], 401));

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('clientes')->group(function () {
        Route::get('/', [ClienteController::class, 'index']);
        Route::post('/', [ClienteController::class, 'store']);
        Route::put('/{id}', [ClienteController::class, 'update']);
        Route::delete('/{id}', [ClienteController::class, 'destroy']);
    });

    Route::prefix('produtos')->group(function () {
        Route::get('/', [ProdutoController::class, 'index']);
        Route::post('/', [ProdutoController::class, 'store']);
        Route::put('/{id}', [ProdutoController::class, 'update']);
        Route::delete('/{id}', [ProdutoController::class, 'destroy']);
    });

    Route::prefix('vendas')->group(function () {
        Route::get('/', [VendaController::class, 'index']);
        Route::get('/{id}', [VendaController::class, 'show']);
        Route::post('/', [VendaController::class, 'store']);
        Route::put('/{id}', [VendaController::class, 'update']);
        Route::delete('/{id}', [VendaController::class, 'destroy']);
    });

    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'index']);
    });
});

Route::prefix('dev')->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
});