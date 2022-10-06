<?php

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
    return redirect()->route('login');
});

Route::get('/add', function () {
    $user = new User();
    $user->nip = 11111;
    $user->name = 'dayat';
    $user->password = Hash::make(11111);
    $user->role = 2;
    $user->save();

    return redirect()->route('login');
});

Route::get('/dashboard', [Controller::class, 'task'])->middleware(['auth'])->name('dashboard');

Route::post('postTodo', [Controller::class, 'post']);
Route::post('changeStatus', [Controller::class, 'status']);
Route::post('updateStaff', [Controller::class, 'updateStaff']);
Route::post('updateTask', [Controller::class, 'updateTask']);
Route::post('taskStatus', [Controller::class, 'taskStatus']);
Route::post('deleteTask', [Controller::class, 'deleteTask']);
Route::post('addStaff', [Controller::class, 'addStaff']);
Route::post('addSpt', [Controller::class, 'addSpt']);
Route::post('updateDasarStatus', [Controller::class, 'updateDasarStatus']);

require __DIR__.'/auth.php';
