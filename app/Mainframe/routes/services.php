<?php
/*---------------------------------------------------------------------------------
| Section: Services routes
|----------------------------------------------------------------------------------
| Services routes are created to be used in your ajax calls. Inside your application.
| Often for vue or axios call you will need custom responses to best handle the situation
|---------------------------------------------------------------------------------*/
// Todo : Write your service(json responses routes here)
Route::prefix('service')->middleware(['request.json', 'auth', 'tenant'])->group(function () {
    $namePrefix = 'service';

    // Example: Following will always return JSON output
    Route::get('users/{user}',
        [\App\Mainframe\Modules\Users\UserController::class, 'show'])->name($namePrefix.'.report.show');
});
