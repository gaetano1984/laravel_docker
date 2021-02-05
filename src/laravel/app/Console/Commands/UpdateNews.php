<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;

class UpdateNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the latest news';

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
     * @return int
     */
    public function handle(NewsService $service)
    {
        $this->info("recupero le url da cui leggere le news");
        foreach(config('news.rss_url') as $testata => $url){
            foreach($url as $u){
                $this->info("leggo da ".$u);
                $tot = $service->UpdateNews($testata, $u);
                $this->info("ho salvato ".$tot." news");    
            }
        }        
        //return 0;
    }
}
