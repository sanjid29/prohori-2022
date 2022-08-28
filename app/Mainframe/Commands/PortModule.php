<?php

namespace App\Mainframe\Commands;

use App\Mainframe\Helpers\Mf;
use App\Module;
use Artisan;
use File;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\ConsoleOutput;

class PortModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "mainframe:port-module 
                            {module : Input the project name i.e 'cart-items'}
                            {--project= : Input the project name i.e. 'SuperDome'}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This updates the class paths in modules table of a given module to match the new project';

    /** @var string */
    private $moduleName;

    /** * @var string */
    private $projectName;

    /**
     * Execute the console command.
     *
     * @return mixed|null
     */
    public function handle()
    {
        $this->moduleName = $this->argument('module');
        $this->projectName = $this->option('project');

        $this->projectName ?? Mf::project();

        $this->info('Porting module:'.$this->moduleName.' to -> Project:'.$this->projectName);

        /*---------------------------------
        | Update modules table
        |---------------------------------*/

        $class = str_replace('-', '', Str::title(Str::singular($this->moduleName)));

        $classDir = 'Projects/'.$this->projectName;
        $classPath = 'Projects\\'.$this->projectName;
        $viewPath = 'projects.'.Str::kebab($this->projectName);

        // Port to mainframe
        if ($this->projectName == 'Mainframe' || $this->projectName == 'mainframe') {
            $classDir = 'Mainframe';
            $classPath = 'Mainframe';
            $viewPath = 'mainframe';
        }

        $module = Module::firstOrNew(['name' => $this->moduleName]);
        // $module->title = str_replace('-', ' ', ucfirst(Str::singular($name)));        // Todo: Give a human friendly name
        // $module->module_group_id = 1;                                                 // Todo: Are you sure you want to put this in default module-group
        // $module->description = 'Manage '.str_replace('-', ' ', Str::singular($name)); // Todo: human friendly name.
        // $module->module_table = 'modules';
        // $module->route_path = 'modules';
        // $module->route_name = 'modules';
        $module->class_directory = 'app/'.$classDir.'/Modules/'.Str::plural($class);
        $module->namespace = '\App\\'.$classPath.'\Modules\\'.Str::plural($class);
        $module->model = $module->namespace.'\\'.$class;
        $module->policy = $module->namespace.'\\'.$class.'Policy';
        $module->processor = $module->namespace.'\\'.$class.'Processor';
        $module->controller = $module->namespace.'\\'.$class.'Controller';
        $module->view_directory = $viewPath.'.modules.'.Str::plural($this->moduleName);
        // $module->icon_css = 'fa fa-ellipsis-v';
        // $module->is_visible = 1;
        // $module->is_active = 1;
        // $module->created_by = 1;

        $module->save();

        $output = new ConsoleOutput();

        $output->writeLn('php artisan cache:clear');
        Artisan::call('cache:clear');

        $output->writeLn('php artisan route:clear');
        Artisan::call('route:clear');

        $output->writeLn('php artisan mainframe:create-root-models');
        Artisan::call('mainframe:create-root-models');

        $this->info('... Done');
    }
}