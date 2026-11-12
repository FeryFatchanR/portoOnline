<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

// Halaman portfolio publik. Admin panel Filament otomatis tersedia di /admin.
Route::get('/', PortfolioController::class)->name('portfolio');
