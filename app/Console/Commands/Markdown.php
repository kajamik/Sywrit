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
    protected $signature = 'Markdown:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert html to markdown';

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
        // convert html article to markdown
        $converter = new \Markdownify\Converter;
        $query = Articoli::get();
        foreach($query as $value) {
          $value->testo = $converter->parseString($value->testo);
          $value->save();
        }
    }
}
