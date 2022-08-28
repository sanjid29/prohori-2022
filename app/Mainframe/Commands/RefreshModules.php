<?php

namespace App\Mainframe\Commands;

use Artisan;

class RefreshModules extends MakeModule
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mainframe:refresh-modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads modules from config to database';

    /**
     * Execute the console command.
     *
     * @return mixed|null
     */
    public function handle()
    {

        $this->info('Loading modules_groups from config to database...');
        $this->info('------------------------------------------');

        Artisan::call('config:clear');
        $config = 'projects.'.projectKey().'.module-groups';

        $items = config($config);

        if (!$items) {
            $this->info('No config found at '.$config);
        } else {

            \DB::table('module_groups')->update(['is_active' => 0, 'is_visible' => 0]);
            foreach ($items as $name => $values) {

                \DB::table('module_groups')->updateOrInsert(
                    ['name' => $name],
                    $values
                );

                $this->info($name.' ... Done');
            }

        }

        $this->info('Loading modules from config to database...');
        $this->info('------------------------------------------');

        $config = 'projects.'.projectKey().'.modules';

        $items = config($config);

        if (!$items) {
            $this->info('No config found at '.$config);
        } else {

            \DB::table('modules')->update(['is_active' => 0, 'is_visible' => 0]);
            foreach ($items as $name => $values) {

                \DB::table('modules')->updateOrInsert(
                    ['name' => $name],
                    $values
                );

                $this->info($name.' ... Done');
            }

        }

        Artisan::call('cache:clear');

    }

}
