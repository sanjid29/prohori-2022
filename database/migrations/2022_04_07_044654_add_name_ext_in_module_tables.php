<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameExtInModuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $modules = \App\Module::getActiveList();

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        foreach ($modules as $module) {
            /** @var \App\Module $module */
            $table = $module->tableName();
            if (!Schema::hasColumn($table, 'name_ext')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->text('name_ext')->nullable()->default(null)->after('name');
                });
                $output->writeln($table.' : name_ext field added');
            } else {
                $output->writeln($table.' : skipped. name_ext field already exists');
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
