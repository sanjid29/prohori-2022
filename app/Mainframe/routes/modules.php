<?php /** @noinspection DuplicatedCode */

use App\Mainframe\Helpers\Mf;
use App\Mainframe\Modules\ModuleGroups\ModuleGroupController;
use App\Mainframe\Modules\Uploads\UploadController;

$modules = Mf::modules();
$moduleGroups = Mf::moduleGroups();
/*
|--------------------------------------------------------------------------
| Common routes for all modules
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'tenant'])->group(function () use ($modules, $moduleGroups) {

    foreach ($modules as $module) {
        $path = $module->route_path;
        $controller = $module->controller;
        // $routeName = $module->route_name;
        $routeName = $module->name; // Note: route name should be same as module name

        Route::get($path.'/{id}/restore', $controller.'@restore')->name($routeName.'.restore');
        Route::get($path.'/datatable/json', $controller.'@datatableJson')->name($routeName.'.datatable-json');
        Route::get($path.'/list/json', $controller.'@listJson')->name($routeName.'.list-json');
        Route::get($path.'/report', $controller.'@report')->name($routeName.'.report');
        Route::get($path.'/{id}/changes', $controller.'@changes')->name($routeName.'.changes');
        Route::get($path.'/{id}/uploads', $controller.'@uploads')->name($routeName.'.uploads.index');
        Route::post($path.'/{id}/uploads', $controller.'@attachUpload')->name($routeName.'.uploads.store');
        Route::post($path.'/{id}/clone', $controller.'@clone')->name($routeName.'.clone');

        /* * Route to add comment file a particular element */
        // Route::get($path.'/{id}/comments', $controller.'@comments')->name($routeName.'.comments.index');
        // Route::post($path.'/{id}/comments', $controller.'@storeComments')->name($routeName.'.comments.store');

        /* * Resourceful route that creates all REST routs. */
        Route::resource($path, $controller)->names([
            'index' => "{$routeName}.index",
            'create' => "{$routeName}.create",
            'store' => "{$routeName}.store",
            'show' => "{$routeName}.show",
            'edit' => "{$routeName}.edit",
            'update' => "{$routeName}.update",
            'destroy' => "{$routeName}.destroy",
        ])->parameters([
            $routeName => 'element', // In case of param name larger than 32 chars
        ]);
    }

    // Module-group index routes
    foreach ($moduleGroups as $moduleGroup) {
        $path = $moduleGroup->route_path;
        Route::get('module-groups/index/'.$path,
            [ModuleGroupController::class, 'home'])->name($moduleGroup->route_name.'.index');
    }

    # Update uploaded file
    Route::post('update-file', [UploadController::class, 'updateExistingUpload'])->name('uploads.update-file');
    # Reorder files
    Route::post('update-upload-order', [UploadController::class, 'reorder'])->name('uploads.reorder');
    # Show image from storage directory
    Route::get('show-image/{id}', [UploadController::class, 'showImage'])->name('show.image');
    # Download
    Route::get('download/{uuid}', [UploadController::class, 'download'])->name('download');
    Route::get('download-zip', [UploadController::class, 'downloadZip'])->name('download.zip');

});