<?php

namespace App\Console\Commands\Schedule;

use Illuminate\Console\Command;
use \Symfony\Component\Console\Output\ConsoleOutput as Console;

use App\Models\Articoli;
use App\Models\DraftArticle;

class Article extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron jobs for schedule articles';

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
        // move article table record to savedArticle
        $query = DraftArticle::where('scheduled_at', '<=', \Carbon\Carbon::now())->get();
        foreach($query as $value) {
          $article = new Articoli();
          $article->topic_id = $value->topic_id;
          $article->titolo = $value->titolo;
          $article->tags = $value->tags;
          $article->testo = $value->testo;
          $article->id_gruppo = $value->id_gruppo;
          $article->id_autore = $value->id_autore;
          $article->license = $value->license;
          $article->save();
          $article->slug = $article->id. '-' .str_slug($article->titolo, '-');
          $article->save();
          $value->delete();
        }

    }
}
