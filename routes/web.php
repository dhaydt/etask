<?php

use App\Http\Controllers\AutentikasController;
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

Route::post('postLogin', [AutentikasController::class, 'postLogin'])->name('postLogin');
Route::post('keluar', [AutentikasController::class, 'logout'])->name('postLogout');
Route::post('reg', [AutentikasController::class, 'registerUser'])->name('reg');
Route::post('checkUser', [AutentikasController::class, 'checkUser'])->name('checkUser');

Route::post('postTodo', [Controller::class, 'post']);
Route::post('changeStatus', [Controller::class, 'status']);
Route::post('updateStaff', [Controller::class, 'updateStaff']);
Route::post('updateTask', [Controller::class, 'updateTask']);
Route::post('taskStatus', [Controller::class, 'taskStatus']);
Route::post('deleteTask', [Controller::class, 'deleteTask']);
Route::post('addSpt', [Controller::class, 'addSpt']);
Route::post('updateDasarStatus', [Controller::class, 'updateDasarStatus']);

Route::post('pegawaiSkpd', [Controller::class, 'getSkpd']);
Route::post('addStaff', [Controller::class, 'addStaff']);

Route::get('generate_spt/{id}', [Controller::class, 'generate_spt'])->name('generate_spt');
Route::get('generate_sppd/{task_id}/{staff_id}', [Controller::class, 'generate_sppd'])->name('generate_sppd');

Route::middleware(['auth.staff'])->group(function () {
    Route::get('/dashboard', [Controller::class, 'task'])->name('dashboard');

    Route::get('staffList', [Controller::class, 'staffList']);
});

require __DIR__.'/auth.php';
