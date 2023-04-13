<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Rutas CRUD para Empleado */
Route::get('employee','App\Http\Controllers\EmployeeController@getEmployees');
Route::get('employee/{id}','App\Http\Controllers\EmployeeController@getEmployee');
Route::post('employee','App\Http\Controllers\EmployeeController@addEmployee');
Route::put('employee/{id}','App\Http\Controllers\EmployeeController@updEmployee');
Route::delete('employee/{id}','App\Http\Controllers\EmployeeController@deleteEmployee');

/* Rutas CRUD para Transaccion */
Route::get('transaction','App\Http\Controllers\TransactionController@getTransactions');
Route::get('transaction/{id}','App\Http\Controllers\TransactionController@getTransaction');
Route::post('transaction','App\Http\Controllers\TransactionController@addTransaction');
Route::put('transaction/{id}','App\Http\Controllers\TransactionController@updTransaction');
Route::delete('transaction/{id}','App\Http\Controllers\TransactionController@deleteTransaction');
