<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Articoli;

class Markdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Article:autosaving';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Article autosaving';

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
     *
     * @return mixed
     */
    public function handle()
    {
        // article autosaving

    }
}
