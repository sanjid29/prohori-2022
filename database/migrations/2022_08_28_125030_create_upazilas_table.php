<?php
/** @noinspection DuplicatedCode */

use App\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateUpazilasTable extends Migration
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
        if (!Schema::hasTable('upazilas')) {

            Schema::create('upazilas', function (Blueprint $table) {

                /******* Default framework fields **********/
                $table->bigIncrements('id');
                $table->string('uuid', 64)->nullable()->default(null)->index();
                $table->unsignedInteger('project_id')->nullable()->default(null)->index();
                $table->unsignedInteger('tenant_id')->nullable()->default(null)->index();
                $table->unsignedInteger('tenant_sl')->nullable()->default(null)->index();
                $table->string('name', 250)->nullable()->default(null)->index();
                $table->string('name_bn', 250)->nullable()->default(null)->index();
                $table->string('code', 02)->nullable()->default(null)->index();
                $table->string('combined_code', 10)->nullable()->default(null)->index();
                $table->unsignedInteger('division_id')->nullable()->default(null)->index();
                $table->string('division_code', 02)->nullable()->default(null);
                $table->string('division_name', 255)->nullable()->default(null)->index();
                $table->unsignedInteger('district_id')->nullable()->default(null)->index();
                $table->string('district_code', 02)->nullable()->default(null);
                $table->string('district_name', 255)->nullable()->default(null)->index();
                $table->double('latitude')->nullable()->default(null);
                $table->double('longitude')->nullable()->default(null);
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
        $name = 'upazilas';

        $module = Module::firstOrNew(['name' => $name]);

        $module->title = str_replace('-', ' ', ucfirst($name));  //Todo: Give a human friendly name
        $module->module_group_id = 1;                                           //Todo
        $module->order = 99;                                                    //Todo
        $module->level = 0;                                                     //Todo
        $module->description = 'Manage '.Str::plural($module->title);           //Todo
        $module->module_table = 'upazilas';
        $module->route_path = 'upazilas';
        $module->route_name = 'upazilas';
        $module->default_route = 'upazilas';
        $module->class_directory = 'app/Projects\Prohori\Modules\AdminArea\Upazilas';
        $module->namespace = '\App\Projects\Prohori\Modules\AdminArea\Upazilas';
        $module->model = '\App\Projects\Prohori\Modules\AdminArea\Upazilas\Upazila';
        $module->policy = '\App\Projects\Prohori\Modules\AdminArea\Upazilas\UpazilaPolicy';
        $module->processor = '\App\Projects\Prohori\Modules\AdminArea\Upazilas\UpazilaProcessor';
        $module->controller = '\App\Projects\Prohori\Modules\AdminArea\Upazilas\UpazilaController';
        $module->view_directory = 'projects.prohori.modules.admin-area.upazilas';
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
        Schema::dropIfExists('upazilas');
    }
}