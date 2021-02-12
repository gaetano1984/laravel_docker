<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\News;
use Illuminate\Http\Request;
use App\Mail\SendNewsAttach;
use App\Services\NewsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    //
    public function download(Request $request, $giornale=null, $anno=null, $mese=null, $giorno=null){
        $path = 'export_pdf';
        $back_url = '';
        if($giornale!=""){
            $path .= "/".$giornale;
        }
        if($anno!=""){
            $back_url .= $giornale;
            $path .= "/".$anno;
        }
        if($mese!=""){
            $back_url .= "/".$anno;
            $path .= "/".$mese;
        }
        if($giorno!=""){
            $path .= "/".$giorno;
            $header = [
                'Content-type:application/pdf'
            ];
            return Storage::disk('ftp')->download($path, 'export.pdf', $header);
        }
        
        $route_path = \str_replace('export_pdf/', '', $path);
        
        $dir = Storage::disk('ftp')->allDirectories($path);
        $files = Storage::disk('ftp')->allFiles($path);

        foreach($dir as $k=>$d){
            $dir[$k] = str_replace('export_pdf/', '', $d);
        }
        foreach($files as $kk=>$f){
            $new_f = str_replace("export_pdf/".$giornale."/".$anno."/".$mese."/", '', $f);
            $files[$kk] = $new_f;
        }

        $offset = 0;
        $last_day = "";

        if($mese!=""){
            sort($files);
            $offset = date('w', strtotime($anno."/".$mese."/01"))-1;
            if($offset==-1){
                $offset=6;
            }
            $last_day = date('t', strtotime($anno.'-'.$mese.'-01'));
        }        

        return view('news.download', compact('path', 'route_path', 'dir', 'files', 'back_url', 'offset', 'last_day', 'mese', 'anno'));
    }
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
