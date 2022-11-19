<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

// Маршруты для задач
Route::resource('tasks', TaskController::class);
Route::put('/tasks/{task}', [TaskController::class, 'change'])->name('tasks.change');
Route::delete('/tasks/{task}/delete', [TaskController::class, 'delete'])->name('tasks.delete');
Route::delete('/tasks/{task}/delete-comments', [TaskController::class, 'deleteComments'])->name('tasks.deleteComments');

// Маршруты для комментариев задач
Route::post('/comments/{task}', [CommentController::class, 'create'])->name('comments.create');
Route::delete('/comments/{comment}', [CommentController::class, 'delete'])->name('comments.delete');

// Маршруты для файлов задач
Route::post('/doc/{task}', [DocumentController::class, 'store'])->name('document.store');

// Маршруты для справочника
Route::resource('users', UserController::class);

// Home page with redirects
//Route::get('/', view('main_page'))->name('main_page');
Route::view('/', 'main_page')->name('main_page');
