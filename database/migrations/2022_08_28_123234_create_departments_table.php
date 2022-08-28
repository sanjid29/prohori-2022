<?php
/** @noinspection DuplicatedCode */

use App\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateDepartmentsTable extends Migration
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
        if (!Schema::hasTable('departments')) {

            Schema::create('departments', function (Blueprint $table) {

                /******* Default framework fields **********/
                $table->bigIncrements('id');
                $table->string('uuid', 64)->nullable()->default(null)->index();
                $table->unsignedInteger('project_id')->nullable()->default(null)->index();
                $table->unsignedInteger('tenant_id')->nullable()->default(null)->index();
                $table->unsignedInteger('tenant_sl')->nullable()->default(null)->index();
                $table->string('name', 250)->nullable()->default(null)->index();
                $table->string('name_bn', 250)->nullable()->default(null)->index();
                $table->string('name_ext', 500)->nullable()->default(null)->index();
                $table->string('slug', 50)->nullable()->default(null)->index();

                /******* Custom columns **********/
                // Todo: Add module specific fields and denormalized fields. In computing, denormalization is the process of
                //  improving the read performance of a database, at the expense of losing some write performance,
                //  by adding redundant copies of data or by grouping it.

                //$table->string('title', 100)->nullable()->default(null);
                //$table->text('field')->nullable()->default(null);
                /*********************************/

                $table->tinyInteger('is_active')->nullable()->default(1);
                $table->unsignedInteger('created_by')->nullable()->default(null)->index();
                $table->unsignedInteger('updated_by')->nullable()->default(null)->index();
                $table->timestamps();
                $table->softDeletes();
                $table->unsignedInteger('deleted_by')->nullable()->default(null)->index();
            });
        }

        /*---------------------------------
        | Update modules table
        |---------------------------------*/
        $name = 'departments';

        $module = Module::firstOrNew(['name' => $name]);

        $module->title = str_replace('-', ' ', ucfirst($name));  //Todo: Give a human friendly name
        $module->module_group_id = 1;                                           //Todo
        $module->order = 99;                                                    //Todo
        $module->level = 0;                                                     //Todo
        $module->description = 'Manage '.Str::plural($module->title);           //Todo
        $module->module_table = 'departments';
        $module->route_path = 'departments';
        $module->route_name = 'departments';
        $module->default_route = 'departments';
        $module->class_directory = 'app/Projects\Prohori\Modules\Departments';
        $module->namespace = '\\App\Projects\Prohori\Modules\Departments';
        $module->model = '\\App\Projects\Prohori\Modules\Departments\Department';
        $module->policy = '\\App\Projects\Prohori\Modules\Departments\DepartmentPolicy';
        $module->processor = '\\App\Projects\Prohori\Modules\Departments\DepartmentProcessor';
        $module->controller = '\\App\Projects\Prohori\Modules\Departments\DepartmentController';
        $module->view_directory = 'projects.prohori.modules.departments';
        $module->icon_css = 'fa fa-cube';
        $module->color_css = 'navy';
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

        // if (config('app.env') == 'local') {
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
        Schema::dropIfExists('departments');
    }
}