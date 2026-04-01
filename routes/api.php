<?php

use App\Http\Controllers\VerbCardController;
use Illuminate\Support\Facades\Route;

Route::get('verbs', [VerbCardController::class, 'apiIndex'])->name('api.verbs.index');
Route::get('verbs/{verb}', [VerbCardController::class, 'apiShow'])->name('api.verbs.show');
