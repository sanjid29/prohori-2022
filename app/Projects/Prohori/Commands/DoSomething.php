<?php

namespace App\Projects\Prohori\Commands;

use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class DoSomething extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:do-something {arg1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Do something! Save users that has a given name";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * Invoice invoice
     *
     * @return mixed
     */
    public function handle()
    {
        $arg1 = $this->argument('arg1');

        $console = new ConsoleOutput();
        $chunk = 100; // Number of rows to process in one chunk
        $count = 0; // Initialize count

        # Get the total number of rows to process
        $query = User::query()->where('is_active', 1)->where('name', $arg1);
        $total = (clone $query)->count();

        # Process by chunk
        $query->orderBy('id')
            ->chunk($chunk, function ($rows) use ($console, $total, &$count) {
                foreach ($rows as $row) {
                    /** @var User $row */
                    $row->saveQuietly();
                    $count++;
                }

                # Show chunk progress in console
                $console->writeln("-- $count/$total rows processed");
            });
    }
}