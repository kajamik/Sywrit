<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Articoli;
use App\Models\SavedArticles;

class OpDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OpDB:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix all database tables';

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
        $query = Articoli::where('status', '0')->get();
        foreach($query as $value) {
          $arch = new SavedArticles();
          $arch->topic_id = $value->topic_id;
          $arch->titolo = $value->titolo;
          $arch->tags = $value->tags;
          $arch->slug = $value->slug;
          $arch->testo = $value->testo;
          $arch->id_gruppo = $value->id_gruppo;
          $arch->id_autore = $value->id_autore;
          $arch->license = $value->license;
          $arch->created_at = $value->created_at;
          $arch->updated_at = $value->updated_at;
          $arch->save();
        }

    }
}
