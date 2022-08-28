<?php

namespace App\Mainframe\Http\Controllers;

use App\Group;
use App\Module;
use App\ModuleGroup;

class SystemController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function moduleNamesConfig()
    {
        $modules = Module::withTrashed()->get();
        echo "<pre>[<br/>";
        foreach ($modules as $module) {
            echo "\"$module->name\",<br/>";
        }

        echo "]<br/></pre>";

        dd();
    }

    public function moduleConfig()
    {

        $modules = Module::withTrashed()->get();

        $skip = [
            "created_by",
            "updated_by",
            "created_at",
            "updated_at",
            "deleted_at",
            "deleted_by"
        ];

        echo "<pre>[<br/>";
        foreach ($modules as $module) {
            echo "\"".trim($module->name)."\"  =>[<br/>";

            foreach ($module->tableColumns() as $column) {
                if (!in_array($column, $skip)) {
                    echo "      \"$column\" => ";

                    $val = $module->$column;

                    if (is_string($val)) {
                        echo "\"".trim(htmlentities(str_replace('"', '\"', $val)))."\"";
                    } else {
                        if (is_null($val)) {
                            echo 'null';
                        } else {
                            echo $val;
                        }
                    }
                    echo ",<br/>";

                }
            }

            echo "  ],<br/>";
        }

        echo "]<br/></pre>";

        dd();

    }

    public function moduleGroupConfig()
    {

        $modules = ModuleGroup::withTrashed()->get();

        $skip = [
            "created_by",
            "updated_by",
            "created_at",
            "updated_at",
            "deleted_at",
            "deleted_by"
        ];

        echo "<pre>[<br/>";
        foreach ($modules as $module) {
            echo "\"$module->name\"  =>[<br/>";

            foreach ($module->tableColumns() as $column) {
                if (!in_array($column, $skip)) {
                    echo "      \"$column\" => ";

                    $val = $module->$column;

                    if (is_string($val)) {
                        echo "\"$val\"";
                    } else {
                        if (is_null($val)) {
                            echo 'null';
                        } else {
                            echo $val;
                        }
                    }
                    echo ",<br/>";

                }
            }

            echo "  ],<br/>";
        }

        echo "]<br/></pre>";

        dd();

    }

    public function userConsts()
    {
        echo "<pre>";
        echo "// Group ids<br/>";
        $groups = Group::withTrashed()->orderBy('id')->get();
        foreach ($groups as $group) {
            echo "public const ".strtoupper(\Str::snake(\Str::camel($group->name)))."_GROUP_ID = ".$group->id."; \n";
        }
        echo "</br>";
        echo "// Group names<br/>";
        foreach ($groups as $group) {
            echo "public const ".strtoupper(\Str::snake(\Str::camel($group->name)))."_GROUP = '".$group->name."'; \n";
        }

        echo "<br/></pre>";

    }

    public function cacheClear()
    {
        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');

        return "Cache Cleared!";
    }
}