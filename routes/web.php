<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "<b>Available endpoints:</b>
    <ul>
    <li>GET       /</li>
    <li>GET       api/customers</li>
    <li>POST      api/customers</li>
    <li>GET       api/customers/{id}</li>
    <li>PUT       api/customers/{id}</li>
    <li>DELETE    api/customers/{id}</li>
    <li>GET       api/customers/{id}/groups</li>
    <li>PUT       api/customers/{id}/groups/{group_id}</li>
    <li>DELETE    api/customers/{id}/groups/{group_id}</li>
    </ul>";
});
