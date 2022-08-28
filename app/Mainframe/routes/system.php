<?php

use App\Mainframe\Http\Controllers\SystemController;

/*
|--------------------------------------------------------------------------
| Mainframe system routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'superuser'])->group(function () {
    Route::get('config/modules', [SystemController::class, 'moduleConfig'])->name('module-config');
    Route::get('config/module-names', [SystemController::class, 'moduleNamesConfig'])->name('module-names-config');
    Route::get('config/module-groups', [SystemController::class, 'moduleGroupConfig'])->name('module-group-config');
    Route::get('config/user-consts', [SystemController::class, 'userConsts'])->name('user-consts');
    Route::get('cache-clear', [SystemController::class, 'cacheClear'])->name('cache-clear');
});


