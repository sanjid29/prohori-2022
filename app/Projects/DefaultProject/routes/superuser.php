<?php

use App\Projects\DefaultProject\Http\Controllers\SuperuserController;

Route::prefix('su')->middleware(['auth', 'verified', 'superuser'])->group(function () {
    Route::get('test', [SuperuserController::class, 'test'])->name('su.test'); // Todo: Remove this Example
});

/*---------------------------------
| Public routes
|---------------------------------*/

