<?php

use App\Http\Controllers\ContestableFundController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Rutas comunes para admin y user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/contestablefunds', [ContestableFundController::class, 'index'])->name('contestablefunds.index');
    Route::get('/contestablefunds/{id}', [ContestableFundController::class, 'show'])->name('contestablefunds.show');

    // Rutas específicas para admin
    Route::middleware(['checkrole:admin'])->group(function () {
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/contestablefunds-create', [ContestableFundController::class, 'create'])->name('contestablefunds.create');
        Route::post('/contestablefunds-store', [ContestableFundController::class, 'store'])->name('contestablefunds.store');
        Route::delete('/contestablefunds/{id}', [ContestableFundController::class, 'destroy'])->name('contestablefunds.destroy');
    });

    Route::middleware(['checkrole:user'])->group(function () {

    });
});

require __DIR__.'/auth.php';
