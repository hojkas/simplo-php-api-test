<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Models\Customer;

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

Route::get('customers', [CustomerController::class, 'index']);
Route::get('customers/{id}', [CustomerController::class, 'show'])->whereNumber('id');
Route::get('customers/{id}/groups', [CustomerController::class, 'showGroups'])->whereNumber('id');
