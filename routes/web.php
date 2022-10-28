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

Route::redirect('/', '/tasks');

Auth::routes();

Route::get('/tasks', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/tasks/create', [App\Http\Controllers\HomeController::class, 'create'])->name('create_task');
