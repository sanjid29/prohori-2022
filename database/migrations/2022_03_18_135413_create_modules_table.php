<?php
/** @noinspection DuplicatedCode */

use App\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*---------------------------------
        | Create the table if doesnt exists
        |---------------------------------*/
        if (!Schema::hasTable('modules')) {

            Schema::create('modules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('uuid', 64)->nullable()->default(null)->index();
                $table->unsignedInteger('project_id')->nullable()->default(null)->index();
                $table->unsignedInteger('tenant_id')->nullable()->default(null)->index();
                $table->string('name', 100)->nullable()->default(null)->index();

                /******* Custom columns **********/
                // Todo: Add module specific fields and denormalized fields. In computing, denormalization is the process of
                //  improving the read performance of a database, at the expense of losing some write performance,
                //  by adding redundant copies of data or by grouping it.

                //$table->string('title', 100)->nullable()->default(null);
                //$table->text('field')->nullable()->default(null);
                /*********************************/

                $table->tinyInteger('is_active')->nullable()->default(1);
                $table->unsignedInteger('created_by')->nullable()->default(null);
                $table->unsignedInteger('updated_by')->nullable()->default(null);
                $table->timestamps();
                $table->softDeletes();
                $table->unsignedInteger('deleted_by')->nullable()->default(null);
            });
        }

        /*---------------------------------
        | Update modules table
        |---------------------------------*/
        $name = 'modules';

        $module = Module::firstOrNew(['name' => $name]);

        $module->title = str_replace('-', ' ', ucfirst($name));        // Todo: Give a human friendly name
        $module->module_group_id = 1;                                                 // Todo: Are you sure you want to put this in default module-group
        $module->description = 'Manage '.Str::plural($module->title); // Todo: human friendly name.
        $module->module_table = 'modules';
        $module->route_path = 'modules';
        $module->route_name = 'modules';
        $module->class_directory = 'app/Projects/DefaultProject/Modules/Modules';
        $module->namespace = '\\App\Projects\DefaultProject\Modules\Modules';
        $module->model = '\\App\Projects\DefaultProject\Modules\Modules\Module';
        $module->policy = '\\App\Projects\DefaultProject\Modules\Modules\ModulePolicy';
        $module->processor = '\\App\Projects\DefaultProject\Modules\Modules\ModuleProcessor';
        $module->controller = '\\App\Projects\DefaultProject\Modules\Modules\ModuleController';
        $module->view_directory = 'projects.default-project.modules.modules';
        $module->icon_css = 'fa fa-ellipsis-v';
        $module->is_visible = 1;
        $module->is_active = 1;
        $module->created_by = 1;

        $module->save();

        /*---------------------------------
        | Run following set of artisan commands
        |---------------------------------*/
        $output = new ConsoleOutput();
        $commands = [
            'cache:clear',
            'route:clear',
            'mainframe:create-root-models',
        ];
        foreach ($commands as $command) {
            $output->writeLn('php artisan '.$command);
            Artisan::call($command);
        }

        // if (env('APP_ENV') == 'local') {
        //     Artisan::call('ide-helper:model -W');
        // }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}