<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexForKeyAndTaInSpreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spreads', function (Blueprint $table) {
            $table->index(['tag','spreadable_type'],'spreadable_type_tag');
            $table->index(['tag','module_id'],'module_id_tag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spreads', function (Blueprint $table) {
            //
        });
    }
}
