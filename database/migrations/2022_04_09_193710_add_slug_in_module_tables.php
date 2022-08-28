<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugInModuleTables extends Migration
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
            if (!Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->text('slug')->nullable()->default(null)->after('name_ext');
                });
                $output->writeln($table.' : slug field added');
            } else {
                $output->writeln($table.' : skipped. slug field already exists');
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

;
