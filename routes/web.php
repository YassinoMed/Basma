<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\VerbCardController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - French Verb Educational Cards
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:10,1');

    Route::get('/register', [AuthController::class, 'createRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegistration'])->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/dashboard', function () {
    return redirect()->route('cards.index');
})->middleware('auth')->name('dashboard');

Route::get('/health', function () {
    $dbOk = true;
    $storageOk = true;

    try {
        DB::connection()->select('select 1');
    } catch (\Throwable) {
        $dbOk = false;
    }

    $storageOk = is_writable(storage_path()) && is_writable(storage_path('logs'));

    $ok = $dbOk && $storageOk;

    return response()->json([
        'status' => $ok ? 'ok' : 'fail',
        'timestamp' => now()->toISOString(),
        'checks' => [
            'db' => $dbOk,
            'storage' => $storageOk,
        ],
    ], $ok ? 200 : 503);
})->name('health');

Route::middleware('auth')->group(function () {
    Route::get('/cards', [VerbCardController::class, 'index'])->name('cards.index');
    Route::get('/cards-v3', [VerbCardController::class, 'indexV3'])->name('cards.index_v3');
    Route::get('/cards/create', [VerbCardController::class, 'create'])->name('cards.create');
    Route::get('/builder', function () {
        return redirect()->route('cards.create');
    })->name('builder');
    Route::post('/cards', [VerbCardController::class, 'store'])->name('cards.store');
    Route::get('/cards/{verb}/duplicate', [VerbCardController::class, 'duplicate'])->name('cards.duplicate');
    Route::get('/cards/print/deck', [VerbCardController::class, 'printDeck'])->name('cards.print_deck');
    Route::get('/cards/print/deck-v3', [VerbCardController::class, 'printDeckV3'])->name('cards.print_deck_v3');
    Route::get('/cards/print/deck-back', [VerbCardController::class, 'printDeckBack'])->name('cards.print_deck_back');
    Route::get('/cards/print/deck-back-v3', [VerbCardController::class, 'printDeckBackV3'])->name('cards.print_deck_back_v3');
    Route::get('/cards/print/single', [VerbCardController::class, 'printSingle'])->name('cards.print_single');
    Route::get('/cards/print/single-v3', [VerbCardController::class, 'printSingleV3'])->name('cards.print_single_v3');
    Route::get('/cards/print/full', [VerbCardController::class, 'printFull'])->name('cards.print_full');
    Route::get('/cards/print/full-v3', [VerbCardController::class, 'printFullV3'])->name('cards.print_full_v3');
    Route::get('/export/anki', [VerbCardController::class, 'exportAnki'])->name('export.anki');
    Route::post('/progress/answer', [VerbCardController::class, 'recordProgress'])->name('progress.answer');
    Route::get('/stats', [VerbCardController::class, 'stats'])->name('stats.index');
    Route::get('/rules', [VerbCardController::class, 'rules'])->name('cards.rules');
    Route::get('/cards/{verb}/v3', [VerbCardController::class, 'showV3'])->name('cards.show_v3');
    Route::get('/cards/{verb}', [VerbCardController::class, 'show'])->name('cards.show');
    Route::get('/cards/{verb}/print', [VerbCardController::class, 'print'])->name('cards.print');
    Route::get('/cards/{verb}/print-v3', [VerbCardController::class, 'printV3'])->name('cards.print_v3');
    Route::get('/cards/{verb}/print-back', [VerbCardController::class, 'printBack'])->name('cards.print_back');
    Route::get('/cards/{verb}/print-back-v3', [VerbCardController::class, 'printBackV3'])->name('cards.print_back_v3');

    Route::get('/favorites', [VerbCardController::class, 'favorites'])->name('favorites.index');
    Route::get('/favorites-v3', [VerbCardController::class, 'favoritesV3'])->name('favorites.index_v3');
    Route::post('/favorites/{verb}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::get('/quiz', [VerbCardController::class, 'quiz'])->name('quiz.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/verbs', [VerbCardController::class, 'adminIndex'])->name('admin.verbs.index');
    Route::get('/verbs/{verb}/edit', [VerbCardController::class, 'adminEdit'])->name('admin.verbs.edit');
    Route::put('/verbs/{verb}', [VerbCardController::class, 'adminUpdate'])->name('admin.verbs.update');
    Route::post('/verbs/{verb}/toggle', [VerbCardController::class, 'adminToggle'])->name('admin.verbs.toggle');
    Route::get('/theme', [ThemeController::class, 'edit'])->name('admin.theme.edit');
    Route::get('/theme-v2', [ThemeController::class, 'editV2'])->name('admin.theme.editV2');
    Route::get('/theme-v3', [ThemeController::class, 'editV3'])->name('admin.theme.editV3');
    Route::get('/theme-v4', [ThemeController::class, 'editV4'])->name('admin.theme.editV4');
    Route::get('/theme-v5', [ThemeController::class, 'editV5'])->name('admin.theme.editV5');
    Route::get('/theme-v6', [ThemeController::class, 'editV6'])->name('admin.theme.editV6');
    Route::get('/theme-v7', [ThemeController::class, 'editV7'])->name('admin.theme.editV7');
    Route::post('/theme', [ThemeController::class, 'update'])->name('admin.theme.update');
    Route::post('/theme/reset', [ThemeController::class, 'reset'])->name('admin.theme.reset');
    Route::get('/system', [SystemController::class, 'index'])->name('admin.system.index');
    Route::post('/system/action', [SystemController::class, 'action'])->name('admin.system.action');
});
