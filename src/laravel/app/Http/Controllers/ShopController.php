<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Services\ShopService;
    use App\Models\Shop;

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
}
