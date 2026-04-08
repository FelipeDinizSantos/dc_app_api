<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::prefix('clientes')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [ClienteController::class, 'index']);
        Route::post('/', [ClienteController::class, 'store']);
    });
});

Route::prefix('produtos')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProdutoController::class, 'index']);
        Route::post('/', [ProdutoController::class, 'store']);
    });
});

Route::prefix('vendas')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [VendaController::class, 'index']);
        Route::get('/{id}', [VendaController::class, 'show']);
        Route::post('/', [VendaController::class, 'store']);
        Route::put('/{id}', [VendaController::class, 'update']);
        Route::delete('/{id}', [VendaController::class, 'destroy']);
    });
});

Route::prefix('usuarios')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [UsuarioController::class, 'index']);
    });
});

// Retirar do app final (?)
Route::prefix('dev')->group(function () {
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::get('/usuarios', [UsuarioController::class, 'index']);
});

// Gambiarra para tratar redirect gerado por falha na autenticação no Insominia
Route::get('/login', fn () => response()->json([
    'message' => 'Não autenticado.',
], 401));
