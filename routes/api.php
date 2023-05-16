<?php

use App\Http\Controllers\Core\AuthController;
use App\Http\Controllers\Core\ExpensesController;
use App\Http\Controllers\Core\RevenuesController;
use App\Http\Controllers\ResumeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::resource('revenues', RevenuesController::class);
    Route::get('revenues/extract/{year}/{month}', [RevenuesController::class, 'extract']);
    Route::resource('expenses', ExpensesController::class);
    Route::get('expenses/extract/{year}/{month}', [ExpensesController::class, 'extract']);
    Route::get('summary/{year}/{month}', [ResumeController::class, 'resume']);
});

