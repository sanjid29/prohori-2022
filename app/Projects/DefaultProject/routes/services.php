<?php

use App\Projects\DefaultProject\Http\Controllers\Services\ServicesController;

/*---------------------------------------------------------------------------------
| Section: Services routes
|----------------------------------------------------------------------------------
| Services routes are created to be used in your ajax calls. Inside your application.
| Often for vue or axios call you will need custom responses to best handle the situation
|---------------------------------------------------------------------------------*/

Route::prefix('service')->middleware(['request.json', 'auth', 'tenant'])->group(function () {
    Route::get('test', [ServicesController::class, 'test'])->name('service.test'); //Todo: Remove this Example
});
