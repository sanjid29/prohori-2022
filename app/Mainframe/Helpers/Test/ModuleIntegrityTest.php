<?php

namespace App\Mainframe\Helpers\Test;

use App\Module;

class ModuleIntegrityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Check if $with and $append fields are empty in a model
     *
     * @return void
     */
    public function test_model_should_not_have_any_append_or_with_attribute()
    {
        $this->print(self::MSG_LOOKING_FOR.'- non-empty $with, $append in models. Default loading should be avoided using $with or/and $append.');
        $modules = Module::getActiveList();

        $except = ['contents'];

        foreach ($modules as $module) {

            /** @var Module $module */
            if (in_array($module->name, $except)) {
                continue;
            }
            /** @var Module $module */
            $this->print('👁️ Module: '.$module->modelClassName());

            $class = '\App\\'.$module->modelClassName();
            $element = new $class;
            $this->assertEmpty($element->getAppends(),
                ' ⚠️'.$class.'::$append attribute should be kept empty. Instead append on runtime $model->append(...)');
            $this->assertEmpty($element->getWith(),
                ' ⚠️'.$class.'::$with attribute should be kept empty. Instead load on runtime $model->load(...)');
        }
    }

}
