<?php

use App\Http\Controllers\AutentikasController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\TaskController;
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

Route::middleware(['auth.staff'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');

    Route::get('staffList', [Controller::class, 'staffList']);
    Route::get('edit-spt', [Controller::class, 'editSpt']);
    Route::post('saveSkpd', [Controller::class, 'saveSkpd']);
});

Route::post('postLogin', [AutentikasController::class, 'postLogin'])->name('postLogin');
Route::post('keluar', [AutentikasController::class, 'logout'])->name('postLogout');
Route::post('reg', [AutentikasController::class, 'registerUser'])->name('reg');
Route::post('checkUser', [AutentikasController::class, 'checkUser'])->name('checkUser');

// TASK
Route::post('postTodo', [TaskController::class, 'addTask']);
Route::post('deleteTask', [TaskController::class, 'deleteTask']);
Route::post('updateTask', [TaskController::class, 'updateTask']);
Route::post('changeStatus', [TaskController::class, 'status']);
Route::post('updateStaff', [TaskController::class, 'updateStaff']);
Route::post('taskStatus', [TaskController::class, 'taskStatus']);

// SPT
Route::post('addSpt', [Controller::class, 'addSpt']);
Route::post('updateDasarStatus', [Controller::class, 'updateDasarStatus'])->name('updateDasarStatus');
Route::post('update-spt', [Controller::class, 'updateSpt'])->name('update.spt');

Route::post('pegawaiSkpd', [Controller::class, 'getSkpd']);
Route::post('addStaff', [Controller::class, 'addStaff']);

Route::get('generate_spt/{id}', [GenerateController::class, 'generate_spt'])->name('generate_spt');
Route::get('generate_sppd/{task_id}/{staff_id}', [GenerateController::class, 'generate_sppd'])->name('generate_sppd');

require __DIR__.'/auth.php';
// tester