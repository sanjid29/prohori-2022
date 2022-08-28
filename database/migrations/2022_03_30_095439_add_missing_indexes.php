<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('changes', function (Blueprint $table) {
            $table->string('changeable_type', 512)->nullable()->default(null)->change();
            $table->index(['changeable_id', 'changeable_type'], 'changeable_composite_index');
            $table->index('changeable_id');
            $table->index('changeable_type');
            $table->index(['module_id', 'element_id'], 'element_composite_index');
            $table->index('module_id');
            $table->index('element_id');
            $table->index('element_uuid');
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->index(['uploadable_id', 'uploadable_type'], 'uploadable_composite_index');
            $table->index('uploadable_id');
            $table->index('uploadable_type');
            $table->index(['module_id', 'element_id'], 'element_composite_index');
            $table->index('module_id');
            $table->index('element_id');
            $table->index('element_uuid');
        });

        Schema::table('spreads', function (Blueprint $table) {
            $table->string('spreadable_type', 512)->nullable()->default(null)->change();
            $table->string('relates_to', 512)->nullable()->default(null)->change();

            $table->index(['spreadable_id', 'spreadable_type'], 'spreadable_composite_index');
            $table->index(['relates_to', 'related_id'], 'relates_to_composite_index');
            $table->index('spreadable_id');
            $table->index('spreadable_type');
            $table->index(['module_id', 'element_id'], 'element_composite_index');
            $table->index('module_id');
            $table->index('element_id');
            $table->index('element_uuid');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('notifiable_id');
            $table->index('notifiable_type');
            $table->index(['module_id', 'element_id'], 'element_composite_index');
            $table->index('module_id');
            $table->index('element_id');
            $table->index('element_uuid');
        });

        Schema::table('user_group', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('group_id');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->index('name');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
