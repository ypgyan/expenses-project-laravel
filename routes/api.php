<?php

use App\Http\Controllers\Core\ExpensesController;
use App\Http\Controllers\Core\RevenuesController;
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

Route::resource('revenues', RevenuesController::class);
Route::get('revenues/extract/{year}/{month}', [RevenuesController::class, 'extract']);
Route::resource('expenses', ExpensesController::class);
Route::get('expenses/extract/{year}/{month}', [ExpensesController::class, 'extract']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

