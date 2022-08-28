<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingIndexesInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');
            $table->index('name');
            $table->index('email');
            $table->index('password');
            $table->index('remember_token');
            $table->index('api_token');
            $table->index('device_token');
            $table->index('phone');
            $table->index('mobile');
            $table->index('auth_token');
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');

            $table->index('name');
            $table->index('slug');
            $table->index('key');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');
            $table->index('name');
            $table->index('slug');
        });

        Schema::table('in_app_notifications', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('event');
            $table->index('announcement_id');
            $table->index('read_at');
            $table->index('is_visible');
            $table->index(['notifiable_id', 'notifiable_type'], 'notifiable_composite_index');
            $table->index('notifiable_id');
            $table->index('notifiable_type');
            $table->index(['module_id', 'element_id'], 'element_composite_index');
            $table->index('module_id');
            $table->index('element_id');
            $table->index('element_uuid');
        });

        Schema::table('push_notifications', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');
            $table->index('user_id');
            $table->index('device_token');
            $table->index('type');
            $table->index('event');
            $table->index('in_app_notification_id');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');
            $table->index('name');
            $table->index('code');
            $table->index('module_id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('tenant_id');
            $table->index('project_id');
            $table->index('name');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
