<?php
/*----------------------------------------------------------------
| Section: Services routes
|-----------------------------------------------------------------
| https://laracasts.com/series/andrews-larabits/episodes/2
|---------------------------------------------------------------*/

Route::prefix('partials')->middleware(['request.json', 'auth', 'tenant'])->group(function () {
    $namePrefix = 'service';

    // Example: Following will always return JSON output
    Route::get('users/{user}',
        [\App\Mainframe\Modules\Users\UserController::class, 'show'])->name($namePrefix.'.report.show');
});
