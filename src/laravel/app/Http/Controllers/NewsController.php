<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Services\NewsService;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\SendNewsAttach;

class NewsController extends Controller
{
    //
    public function index(NewsService $newsService){
        $id_user = Auth::user()->id;
    	$news = $newsService->paginateNews($id_user, 10);
    	return view('news.index', compact('news'));
    }
    public function favorite(NewsService $newsService){
    	$favorites = $newsService->getFavorites()->toArray();
    	$source = array_keys(config('news.rss_url'));
        
        $obj = [];
        foreach($source as $s){
            $arr = ['source' => $s, 'favorites' => false];
            if(array_search($s, array_column($favorites, 'source'))!==false){
                $arr['favorites'] = true;
            }
            array_push($obj, $arr);
        }
    	return view('news.favorites', compact('obj')); 
    }
    public function postFavorite(Request $request, NewsService $newsService){
        $favs = $request->get('favorites');
        $newsService->updateFavorites($favs);
        return back();
    }
}
