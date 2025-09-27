<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServerController; // Asegúrate de importar el controlador

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Esta línea mágica crea todas las rutas CRUD para tu API de servidores
Route::apiResource('servers', ServerController::class);
Route::post('/servers/update-order', [ServerController::class, 'updateOrder']);