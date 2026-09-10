<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstatisticaController;
use App\Http\Controllers\Admin\EstatisticaAdminController;
use App\Http\Controllers\Admin\LicenciarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [EstatisticaController::class, 'home'])->name('home');

Route::redirect('dashboard', '/admin/estatisticas')->middleware(['auth'])->name('dashboard');

Route::get('/explorar', [EstatisticaController::class, 'index'])->name('explorar');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('estatisticas', [EstatisticaAdminController::class, 'index'])->name('admin.estatisticas.index');
    Route::post('estatisticas', [EstatisticaAdminController::class, 'store'])->name('admin.estatisticas.store');
    Route::put('estatisticas/{estatistica}', [EstatisticaAdminController::class, 'update'])->name('admin.estatisticas.update');
    Route::delete('estatisticas/{estatistica}', [EstatisticaAdminController::class, 'destroy'])->name('admin.estatisticas.destroy');
});

use App\Http\Controllers\EscolaRegisterController;

Route::get('/escola/registrar', [EscolaRegisterController::class, 'create'])->name('escola.register');
Route::post('/escola/registrar', [EscolaRegisterController::class, 'store'])->name('escola.register.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/escola/licenciar', [LicenciarController::class, 'index'])->name('escola.licenciar');

    Route::post('/escolas/{escola}/declaracao', [EscolaRegisterController::class, 'saveDeclaracao'])
        ->name('escolas.declaracao.save');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
