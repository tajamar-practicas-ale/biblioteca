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
