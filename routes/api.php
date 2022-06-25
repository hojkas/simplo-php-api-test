<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

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
Route::get('customers/{id}', [CustomerController::class, 'show'])
    ->whereNumber('id');
Route::get('customers/{id}/groups', [CustomerController::class, 'showGroups'])
    ->whereNumber('id');
Route::post('customers', [CustomerController::class, 'store']);
Route::put('customers/{id}', [CustomerController::class, 'update'])
    ->whereNumber('id');
Route::delete('customers/{id}', [CustomerController::class, 'destroy'])
    ->whereNumber('id');
Route::put('customers/{id}/groups/{group_id}', [CustomerController::class, 'add_to_group'])
    ->whereNumber('id')
    ->whereNumber('group_id');
Route::delete('customers/{id}/groups/{group_id}', [CustomerController::class, 'remove_from_group'])
    ->whereNumber('id')
    ->whereNumber('group_id');
