<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\News;
use Illuminate\Http\Request;
use App\Mail\SendNewsAttach;
use App\Services\NewsService;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    //
    public function index(Request $request, NewsService $newsService){
        $url = url()->current();
        $giornale = $request->has('giornale') && $request->get('giornale')!="" && $request->get('giornale')!=null ? $request->get('giornale') : null;        
        $sources = array_keys(config('news.rss_url'));

        if($request->has('page')){
            $url .= "?page=".$request->get('page');
        }
        if($giornale!=null){
            $url .= "&giornale=".$giornale;
        }

        $id_user = Auth::user()->id;
    	$news = $newsService->paginateNews($id_user, 10, $giornale);
    	return view('news.index', compact('news', 'url', 'giornale', 'sources'));
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
