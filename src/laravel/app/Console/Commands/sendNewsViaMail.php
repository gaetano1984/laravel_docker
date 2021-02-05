<?php

namespace App\Console\Commands;

use App\Services\NewsService;
use Illuminate\Console\Command;

class sendNewsViaMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:sendViaMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send a pdf containing news of the previous day';

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
    public function handle(NewsService $newsService)
    {
        //return 0;
        $newsService->sendViaMail();
    }
}
