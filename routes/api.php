<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

// Rutas RESTful para la API
Route::apiResource('users', UserController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('loans', LoanController::class);

Route::post('/loans/{id}/return', [LoanController::class, 'returnLoan']);

// Trampa para el error 405 de ruta no definida
use Illuminate\Http\Request;

Route::put('/books', function (Request $request) {
    return response()->json([
        'message' => 'Debes enviar el ID del libro (ej. PUT /api/books/4)',
    ], 200);
});

// Capturar DELETE a /api/books sin ID
Route::delete('/books', function (Request $request) {
    return response()->json([
        'message' => 'DELETE /books sin ID recibido — ignorado',
    ], 200);
});
