<?php

    namespace App\Services;

    use App\Repository\ShopRepository;

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
    }