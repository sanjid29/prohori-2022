<?php

namespace App\Mainframe\Commands;

use App\Upload;
use File;

class CleanDeletedUploads extends MakeModule
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mainframe:clean-deleted-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently remove deleted upload entries';

    /**
     * Execute the console command.
     *
     * @return mixed|null
     */
    public function handle()
    {

        $this->info('Deleting ..');

        Upload::withTrashed()->whereNotNull('deleted_at')->chunk('10', function ($uploads) {

            /** @var Upload $upload */
            foreach ($uploads as $upload) {
                $path = public_path($upload->path);
                if (File::exists($path)) {
                    // Note: Delete from /public/...
                    $this->info("Exists [{$upload->id}]".$path);
                    File::delete($path);
                } elseif (\Storage::exists($upload->path)) {
                    // Note: Delete from /storage/app/file/...
                    $this->info("Exists [{$upload->id}]".$path);
                    \Storage::delete($upload->path);
                } else {
                    $this->info("Does not exist [{$upload->id}]".$path);
                }

                $upload->forceDelete();
            }
        });

        $this->info('... Done');

    }

}
