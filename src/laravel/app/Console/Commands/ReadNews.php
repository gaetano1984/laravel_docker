<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;
use Illuminate\Support\Facades\DB;

class ReadNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:read {--giornale=*} {--limit=} {--category=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read News';

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
        $giornale = $this->option('giornale');
        $limit = $this->option('limit');
        $category = $this->option('category');

        $obj = [
            'scope' => [
                DB::raw('trim(title)')
                ,'giornale'
                ,'category'
                ,DB::raw('date_format(pubDate, \'%Y-%m-%d\')')
            ]
            ,'filter' => ['giornale' => $giornale, 'category' => $category]
            ,'limit' => $limit
        ];

        $news = $service->readNews($obj);
        $this->table(['giornale', 'titolo', 'categoria', 'data'], $news);
        //return 0;
    }
}
