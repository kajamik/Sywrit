<?php

namespace App\Console\Commands\Article;

use Illuminate\Console\Command;
use \Symfony\Component\Console\Output\ConsoleOutput as Console;

use App\Models\Articoli;
use App\Models\DraftArticle;

class Image extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina le immagini inutili degli articoli';

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
        $query = Articoli::get();
        foreach($query as $value) {
        }
        $query = DraftArticle::get();
        foreach($query as $value) {
        }

    }
}
