<?php

use App\Mainframe\Modules\Modules\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Note: Skip if the table exists
        if (Schema::hasTable('settings')) {
            return;
        }
        /*
         * Insert into modules table
         */
        $name = 'settings';
        /** @var Module $module */
        $module = Module::where('name', $name)->first();

        $module->title = str_replace('-', ' ', ucfirst(Str::singular($name)));
        $module->module_group_id = 1;
        $module->description = 'Manage '.str_replace('-', ' ', Str::singular($name));
        $module->module_table = 'settings';
        $module->route_path = 'settings';
        $module->route_name = 'settings';
        $module->class_directory = 'app/Projects/Prohori/Modules/Settings';
        $module->namespace = '\App\Projects\Prohori\Modules\Settings';
        $module->model = '\App\Projects\Prohori\Modules\Settings\Setting';
        $module->policy = '\App\Projects\Prohori\Modules\Settings\SettingPolicy';
        $module->processor = '\App\Projects\Prohori\Modules\Settings\SettingProcessor';
        $module->controller = '\App\Projects\Prohori\Modules\Settings\SettingController';
        $module->view_directory = 'projects.prohori.modules.settings';
        $module->is_visible = 1;
        $module->is_active = 1;
        $module->created_by = 1;

        $module->save();

        Artisan::call('cache:clear');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
