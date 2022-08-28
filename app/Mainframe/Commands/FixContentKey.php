<?php

namespace App\Mainframe\Commands;

use App\Content;

class FixContentKey extends MakeModule
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mainframe:fix-content-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change name:content -> key:value';

    /**
     * Execute the console command.
     *
     * @return mixed|null
     */
    public function handle()
    {

        $this->info('Changing name:content -> key:value...');

        \DB::table('contents')->latest()->chunk(10, function ($contents) {

            /** @var Content $upload */
            foreach ($contents as $content) {

                $str = multipleStrReplace($content->parts, ['name' => 'key']);
                $str = multipleStrReplace($str, ['content' => 'value']);

                $this->info('#'.$content->id. ' > '.$str);

                $count = \DB::table('contents')->where('id',$content->id)->update(['parts'=>$str]);

                if($count){
                    $this->info('... Updated');
                }

            }
        });

        $this->info('... Done');
    }

}
