<?php

use App\Http\Controllers\TaskController;
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

Auth::routes();

Route::resource('tasks', TaskController::class);
Route::put('/tasks/{task}', [TaskController::class, 'change'])->name('tasks.change');
//Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::redirect('/', route('tasks.index'));
