<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CourseController;

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
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'permission:manage-courses'])->group(function () {
    Route::get('/admin/courses/create', [CourseController::class, 'create']);
    Route::post('/admin/courses', [CourseController::class, 'store']);
});

Route::middleware(['auth', 'role:eleve'])->group(function () {
    Route::get('/eleve/dashboard', [DashboardController::class, 'eleve'])
        ->name('dashboard.eleve');
});

Route::middleware(['auth', 'role:enseignant'])->group(function () {
    Route::get('/enseignant/dashboard', [DashboardController::class, 'enseignant'])
        ->name('dashboard.enseignant');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superadmin'])
        ->name('dashboard.superadmin');
});
