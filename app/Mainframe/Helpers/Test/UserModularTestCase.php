<?php

namespace App\Mainframe\Helpers\Test;

use App\Module;
use App\User;
use Tests\TestCase;

class UserModularTestCase extends UserTestCase
{
    use ModularJsonTestTrait, ModularHttpTestTrait;

    /**
     * The module name that is being tested
     *
     * @var string
     */
    public $moduleName;

    /**
     * @var \App\Mainframe\Modules\Modules\Module
     */
    public $module;

    /**
     * Executes at the beginning of the class
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        fwrite(STDOUT, __METHOD__."\n");
    }

    /**
     * Executes at the end of the class
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        (new self())->setUp(); // Note: Need to instantiate the laravel app to access the classes

        fwrite(STDOUT, __METHOD__."\n");
        //        // Delete test entries
        //        fwrite(STDOUT, "🧹 Clean test data ... ");
        //        Division::where('name', 'LIKE', '%TEST--%')->whereNull('name')->forceDelete();
        //        fwrite(STDOUT, "Done \n");
    }

    /**
     * Set up the class. This works like constructor and run
     * before every test method
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->module = Module::byName($this->moduleName);
        $this->user = User::remember(timer('long'))->find(env('API_USER_ID'));
        $this->be($this->user); // Impersonate as the currently created admin user
    }

    /*
    |--------------------------------------------------------------------------
    | Section: Helpers
    |--------------------------------------------------------------------------
    |
    | These are not actual tests rather helpers to fun the tests.
    |
    */

    /**
     * Input fields based on form order. These inputs will be used to save a new element
     * These fields should also be available in the HTML form.
     *
     * @return array
     */
    public function inputs()
    {
        return [
            'name' => $this->testPrefix,
            'is_active' => 1
        ];
    }

    /**
     * Default error message that are expected when the form is posted with no input.
     *
     * @return string[]
     */
    public function defaultErrors()
    {
        return [
            //"Failed to create new " . \Str::singular($this->module->title),
            "The name field is required.",
            "The is_active field is required."
        ];
    }

    /**
     * Value of the elemnt that are updated through test.
     *
     * @return array
     */
    public function updateValues()
    {
        $latest = $this->latest();

        return [
            'name' => 'UPDATED '.$latest->name,
        ];
    }

    /**
     * Grid column names in required order. These should be available in datatable/grid table header
     *
     * @return string[]
     */
    public function gridColumns()
    {
        return ['ID', 'Name', 'Updater', 'Updated at', 'Active'];
    }

    /**
     * Array of HTML markups created for each input. This HTML should be available in the form
     *
     * @return array
     */
    public function inputHtmlMarkupTexts()
    {
        return collect($this->inputs())->keys()
            ->map(function ($item, $key) {
                return 'id="'.$item.'"';   // Note: this produces string 'id="name"'. You may produce a different search string.
            })->all();
    }

    public function cleanTestData($table = null)
    {
        $table = $table ?: $this->module->tableName();
        // Delete test entries
        fwrite(STDOUT, "🧹 Clean test data from table : {$table} ... ");
        \DB::table($table)->where('name', 'LIKE', '%'.$this->testPrefix.'%')->orWhereNull('name')->delete();
        fwrite(STDOUT, "Done \n");

        return $this;
    }

    public function migrateLegacyData($mapper = null)
    {
        $mapper = $mapper ?: \Str::studly($this->module->name).'Mapper';
        // Load existing data
        fwrite(STDOUT, "📦 Load existing data using mapper ... ");
        \Artisan::call('project:migrate-legacy-data '.$mapper);
        fwrite(STDOUT, "Done \n");

        return $this;
    }

}


