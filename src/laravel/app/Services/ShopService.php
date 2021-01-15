<?php

    namespace App\Services;

    use App\Repository\ShopRepository;
    use App\Jobs\ExportShops;

    class ShopService{
        public $shopRepository;
        public function __construct(ShopRepository $shopRepository){
            $this->shopRepository = $shopRepository;
        }
        public function list(){
            return $this->shopRepository->list();
        }
        public function create($ragione_sociale, $indirizzo, $aperto){
            return $this->shopRepository->create($ragione_sociale, $indirizzo, $aperto);
        }
        public function shopList(){
            $list = $this->shopRepository->shopList();
            return $list;
        }
        public function find($id){
            $shop = $this->shopRepository->find($id);
            return $shop;
        }
        public function update($id, $ragione_sociale, $indirizzo, $aperto){
            return $this->shopRepository->update($id, $ragione_sociale, $indirizzo, $aperto);
        }
        public function export($shop){
            \Log::info("inizio l'export degli shop");
            $csv = \storage_path('export/shops/export_shops_'.rand(1000,2000).'.csv');

            $fh = fopen($csv, 'w');
            foreach($shop as $s){
                \fputcsv($fh, $s);
            }
            fclose($fh);

            \Log::info("termino l'export degli shop");           
        }      
    }