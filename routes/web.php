<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Gambiarra para tratar redirect gerado por falha na autenticação Insominia 
Route::get('/login', fn () => response()->json([
    'message' => 'Não autenticado.'
], 401));