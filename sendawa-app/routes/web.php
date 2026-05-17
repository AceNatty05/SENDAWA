<?php

use App\Livewire\ReviewManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| SENDAWA — Aplikasi Postingan Anonim
| Semua route diarahkan ke komponen Livewire PostinganManager.
|
*/

Route::get('/', ReviewManager::class)->name('home');
