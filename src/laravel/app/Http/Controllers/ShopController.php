<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Services\ShopService;
    use App\Http\Requests\Api\Shop;
    use App\Jobs\ExportShops;

    class ShopController extends Controller
    {
        public $shopService;
        public function create(){
            return view('shop.create');
        }
        public function shopCreate(Request $request, ShopService $shopService){
            $this->validate($request, Shop::shopCreate());

            $ragione_sociale = $request->get('ragione_sociale');
            $indirizzo = $request->get('indirizzo');
            $aperto = $request->get('stato');

            $shopService->create($ragione_sociale, $indirizzo, $aperto);
            return response()->json(['res' => 'ok']);
        }
        public function list(){
            return view('shop.list');
        }
        public function shopList(ShopService $shopService){
            $shops = $shopService->shopList();
            return \response()->json($shops);
        }
        public function edit($id, ShopService $shopService){
            $shop = $shopService->find($id);
            return view('shop.edit', compact('shop'));
        }
        public function shopUpdate(Request $request, ShopService $shopService){
            $this->validate($request, Shop::shopUpdate());

            $id = $request->get('shop_id');
            $ragione_sociale = $request->get('ragione_sociale');
            $indirizzo = $request->get('indirizzo');
            $aperto = $request->get('stato');

            $shopService->update($id, $ragione_sociale, $indirizzo, $aperto);
            return response()->json(['res' => 'ok']);
        }
        public function export(Request $request, ShopService $shopService){
            $shop = $shopService->shopList();
            ExportShops::dispatch($shop)->onQueue('shop_export');
        }
    }
