<?php

namespace App\Mainframe\Commands;

use App\Upload;
use File;

class CleanTempDirectory extends MakeModule
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mainframe:clean-temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently remove public/temp entries';

    /**
     * Execute the console command.
     *
     * @return mixed|null
     */
    public function handle()
    {

        $path = public_path('temp');

        $this->info('Deleting ..'.$path);

        $filesInFolder = \File::files($path);

        foreach ($filesInFolder as $path) {
            // $file = pathinfo($path);
            $this->info($path);
            File::delete($path);

        }

        $this->info('... Done');

    }

}
