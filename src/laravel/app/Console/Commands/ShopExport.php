<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ExportShops;
use App\Repository\ShopRepository;

class ShopExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shop Export';

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
    public function handle(ShopRepository $shopRepository)
    {
        $shops = $shopRepository->shopList();
        ExportShops::dispatch($shops)->onQueue('shop_export');
    }
}
