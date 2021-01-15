<?php

    namespace App\Repository;

    use App\Models\Shop;
    use Illuminate\Support\Facades\DB;

    class ShopRepository{
        public $shop;
        public function __construct(Shop $shop){
            $this->shop = $shop;
        }
        public function list(){
            return $this->shop->get();
        }
        public function create($ragione_sociale, $indirizzo, $aperto){
            DB::transaction(function() use ($ragione_sociale, $indirizzo, $aperto){
                $s = new Shop();
                $s->ragione_sociale = $ragione_sociale;
                $s->indirizzo = $indirizzo;
                $s->aperto = $aperto;
                $s->save();
            });            
        }
        public function shopList(){
            return $this->shop->get()->toArray();
        }
        public function find($id){
            $shop = $this->shop->find($id);
            return $shop->toArray();
        }
        public function update($id, $ragione_sociale, $indirizzo, $aperto){
            DB::transaction(function() use ($id, $ragione_sociale, $indirizzo, $aperto){
                $shop = $this->shop->find($id);

                $shop->ragione_sociale = $ragione_sociale;
                $shop->indirizzo = $indirizzo;
                $shop->aperto = $aperto;
    
                $shop->save();
            });            
        }
    }