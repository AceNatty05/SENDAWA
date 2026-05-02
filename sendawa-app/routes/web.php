<?php

use App\Livewire\ReviewManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| SENDAWA — Aplikasi Review Anonim
| Semua route diarahkan ke komponen Livewire ReviewManager.
|
*/

Route::get('/', ReviewManager::class)->name('home');
