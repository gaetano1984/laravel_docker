<?php

    namespace App\Repository;

    use App\Models\Shop;

    class ShopRepository{
        public $shop;
        public function __construct(Shop $shop){
            $this->shop = $shop;
        }
        public function list(){
            return $this->shop->get();
        }
        public function create($ragione_sociale, $indirizzo, $aperto){
            $s = new Shop();
            $s->ragione_sociale = $ragione_sociale;
            $s->indirizzo = $indirizzo;
            $s->aperto = $aperto;
            $s->save();
        }
        public function shopList(){
            return $this->shop->get()->toArray();
        }
    }