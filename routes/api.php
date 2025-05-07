<?php

use App\Http\Controllers\ClientesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/clientes', [ClientesController::class, 'clientes']);
Route::get('/cliente', [ClientesController::class, 'obtenerCliente']);

Route::post('/cliente', [ClientesController::class, 'crearCliente']);

Route::put('/cliente', [ClientesController::class, 'actualizarCliente']);
Route::delete('/cliente', [ClientesController::class, 'eliminarClientes']);
