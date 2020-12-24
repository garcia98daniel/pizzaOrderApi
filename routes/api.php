<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('admin/pedidos', [\App\Http\Controllers\OrderController::class, 'index']);
Route::get('admin/historial/{startdate?}/{enddate?}', [\App\Http\Controllers\OrderController::class, 'getOrdersByDate']);
Route::get('admin/total-ventas/{startdate?}/{enddate?}', [\App\Http\Controllers\OrderController::class, 'getTotalSalesInAday']);
// Route::get('admin/pedidos/{orderId}', [\App\Http\Controllers\OrderController::class, 'show']);
Route::post('admin/pedidos', [\App\Http\Controllers\OrderController::class, 'store']);

Route::match(['put', 'patch'], 'admin/pedidos/edit-status/{orderId}', [\App\Http\Controllers\OrderController::class, 'update']);
Route::delete('admin/pedidos/delete/{orderId}', [\App\Http\Controllers\OrderController::class, 'destroy']);

