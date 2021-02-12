<?php

    namespace App\Repository;

    use App\Models\News;
    use Illuminate\Support\Facades\DB;

    class NewsRepository{
        public function __construct(){

        }
        public function removeDuplicate(){
            $id_news = News::select(DB::raw('max(id_news)'))
                ->groupBy('guid')
                ->having(DB::raw('max(id_news)', '>', '1'))
                ->get()->pluck(DB::raw('max(id_news)'))->toArray()
            ;
            News::whereIn('id_news', $id_news)->delete();
        }
        public function filterNews($n, $giornale){
            $news = News::whereIn('giornale', $giornale)->orderBy('pubDate', 'desc')->paginate($n);
            return $news;
        }
        public function exportToPdf(){
            $news = News::select(
                '*', 
				DB::raw('date_format(pubDate, \'%Y-%m-%d\') as data'),
				DB::raw('date_format(pubDate, \'%Y\') as anno'),
				DB::raw('date_format(pubDate, \'%m\') as mese'),
				DB::raw('date_format(pubDate, \'%d\') as giorno'),
            )->get()->toArray();
            return $news;
        }
        public function readNews($obj){
            $news = News::select($obj['scope']);
			if($obj['filter']['category']!="" && $obj['filter']['category']!=null){
				$news = $news->where('category', 'like', '%'.$obj['filter']['category'].'%');	
			}
			if($obj['filter']['giornale']!="" && $obj['filter']['giornale']!=null){
				$news = $news->whereIn('giornale', $obj['filter']['giornale']);
			}
			if($obj['limit']!=null){
				$news = $news->limit($obj['limit']);
			}
			$news = $news->orderBy('pubDate', 'desc')->get()->toArray();
            return $news;
        }
        public function updateNews($news){
            $saved=0;
            $check = News::where(['guid' => $news->guid])->get();
            if($check->count()==0){
                $n = new News();
                $n->title = strip_tags($news->title);
                $n->pubDate = date('Y-m-d H:i:s', strtotime($news->pubDate));
                $n->guid = $news->guid;
                $n->description = trim(strip_tags($news->description));
                $n->category = $news->category;
                $n->giornale = $testata;
                $n->save();
                $saved=1;
            }
            return $saved;
        }
    }

?>