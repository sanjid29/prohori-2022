<?php
/** @noinspection DuplicatedCode */

use App\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateSuperHeroesTable extends Migration
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
        if (!Schema::hasTable('{table}')) {

            Schema::create('{table}', function (Blueprint $table) {

                /******* Default framework fields **********/
                $table->bigIncrements('id');
                $table->string('uuid', 64)->nullable()->default(null)->index();
                $table->unsignedInteger('project_id')->nullable()->default(null)->index();
                $table->unsignedInteger('tenant_id')->nullable()->default(null)->index();
                $table->unsignedInteger('tenant_sl')->nullable()->default(null)->index();
                $table->string('name', 250)->nullable()->default(null)->index();
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
        $name = '{module_name}';

        $module = Module::firstOrNew(['name' => $name]);

        $module->title = str_replace('-', ' ', ucfirst($name));  //Todo: Give a human friendly name
        $module->module_group_id = 1;                                           //Todo
        $module->order = 99;                                                    //Todo
        $module->level = 0;                                                     //Todo
        $module->description = 'Manage '.Str::plural($module->title);           //Todo
        $module->module_table = '{table}';
        $module->route_path = '{route_path}';
        $module->route_name = '{route_name}';
        $module->default_route = '{route_name}';
        $module->class_directory = '{class_directory}';
        $module->namespace = '{namespace}';
        $module->model = '{model}';
        $module->policy = '{policy}';
        $module->processor = '{processor}';
        $module->controller = '{controller}';
        $module->view_directory = '{view_directory}';
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
        Schema::dropIfExists('super_heroes');
    }
}