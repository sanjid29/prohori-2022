<?php

use App\Projects\DefaultProject\Http\Controllers\TestController;

Route::prefix('test')->middleware(['auth', 'verified', 'superuser'])->group(function () {
    Route::get('config/modules', [TestController::class, 'test'])->name('test'); // Sample

    # Section: Email content preview
    Route::prefix('preview')->group(function () {
        // {app}/test/preview
        Route::get('email/user-email-verification/{id}', [TestController::class, 'previewUserVerifyEmail']);
        Route::get('email/user-reset-password/{id}', [TestController::class, 'previewUserResetPasswordEmail']);
        // Todo : add new test routes here
    });
});


