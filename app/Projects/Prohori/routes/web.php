<?php

use App\Projects\Prohori\Http\Controllers\DataBlockController;
use App\Projects\Prohori\Http\Controllers\DatatableController;
use App\Projects\Prohori\Http\Controllers\HomeController;
use App\Projects\Prohori\Http\Controllers\ReportController;

// Route::get('/', [HomeController::class, 'index'])->name('home'); // Code: Enabling this takes root url to a public page

Route::middleware(['auth', 'verified', 'tenant'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home'); // Code: Enable this takes root url to login page
    Route::get('data/{key}', [DataBlockController::class, 'show'])->name('data-block.show');
    Route::get('report/{key}', [ReportController::class, 'show'])->name('report');
    Route::get('datatable/{key}', [DatatableController::class, 'show'])->name('datatable.json');
    /*---------------------------------
    | Project specific routs
    |---------------------------------*/
    // Todo : Write new routes for your project

    // Route::get('user-details/{id}', [HomeController::class, 'userDetails']);
});

/*---------------------------------
| Public routes
|---------------------------------*/
// Todo : Write any public routes for your project
Route::prefix('public')->group(function () {
    Route::get('/', [HomeController::class, 'public']);
});