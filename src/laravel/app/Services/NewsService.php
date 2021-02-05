<?php 

	namespace App\Services;

	use Dompdf\Dompdf;
	use App\Models\News;
	use App\Mail\SendNewsAttach;
	use App\Models\News\NewsUser;
	use App\Models\FavoriteSources;
	/*use Longman\TelegramBot\Request;
	use Longman\TelegramBot\Telegram;*/
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Http;

	class NewsService{
		public $telegram_config;
		public function __construct(){
			/*$this->telegram_config = [
				'chat_id' => config('telegram.chat_id')
				,'text' => ''
			];*/
		}
		public function sendViaMail(){
			$m = new FavoriteSources();
			$arr = [];
			$f = $m->select('id_user', 'source')->get()->toArray();
			foreach($f as $f_temp){
				if(!array_key_exists($f_temp['id_user'], $arr)){
					$arr[$f_temp['id_user']] = [
						'source' => []
						,'attach' => []
					];
				}
				array_push($arr[$f_temp['id_user']]['source'], $f_temp['source']);
			}
			$y = date('Y', strtotime('-1 days'));
			$m = date('m', strtotime('-1 days'));
			$d = date('d', strtotime('-1 days'));

			foreach($arr as $id_user=>$arr_temp){
				foreach($arr_temp['source'] as $s){
					$file = storage_path('export_pdf/'.$s.'/'.$y.'/'.$m.'/'.$d.'.pdf');
					if(file_exists($file)){
						array_push($arr[$id_user]['attach'], $file);
					}
				}
			}
			foreach($arr as $id_user=>$arr_temp){
				$u = NewsUser::find($id_user);
				\Mail::to($u->email)->send(new SendNewsAttach($arr[$id_user]['attach']));
			}
		}
		public function getFavorites(){
			$id_user = Auth::User()->id;
			$fsources = NewsUser::where('id', $id_user)->get()->first()->favoriteSources()->get();
			return $fsources;
		}
		public function paginateNews($id_user, $n){
			$favs = NewsUser::find($id_user)->favoriteSources()->get()->toArray();
			$news = $this->filterNews($favs, $n);
			return $news;
		}
		public function filterNews($favs, $n){
			$f_arr = [];
			foreach($favs as $f){
				array_push($f_arr, $f['source']);
			}
			$news = News::whereIn('giornale', $f_arr)->orderBy('pubDate', 'desc')->paginate($n);
			return $news;
		}
		public function exportToPdf(){

			$news = News::select([
				'*', 
				DB::raw('date_format(pubDate, \'%Y-%m-%d\') as data'),
				DB::raw('date_format(pubDate, \'%Y\') as anno'),
				DB::raw('date_format(pubDate, \'%m\') as mese'),
				DB::raw('date_format(pubDate, \'%d\') as giorno'),
			])->get()->toArray();

			$arr = [];
			foreach($news as $n){
				if(!array_key_exists($n['giornale'], $arr)){
					$arr[$n['giornale']] = [];
				}
				if(!array_key_exists($n['anno'], $arr[$n['giornale']])){
					$arr[$n['giornale']][$n['anno']] = [];
				}
				if(!array_key_exists($n['mese'], $arr[$n['giornale']][$n['anno']])){
					$arr[$n['giornale']][$n['anno']][$n['mese']] = [];
				}
				if(!array_key_exists($n['giorno'], $arr[$n['giornale']][$n['anno']][$n['mese']])){
					$arr[$n['giornale']][$n['anno']][$n['mese']][$n['giorno']] = [];
				}
				array_push(
					$arr[$n['giornale']][$n['anno']][$n['mese']][$n['giorno']],
					[
						'title' => $n['title']
						,'description' => $n['description']
						,'guid' => $n['guid']
					]
				);
			}

			foreach($arr as $giornale=>$arr_a){
				foreach($arr_a as $anno=>$arr_b){
					foreach($arr_b as $mese=>$arr_c){
						foreach($arr_c as $giorno=>$arr_d){
							$html = "<html><body>";
							foreach($arr_d as $news_temp){
								$html .= "<div><b><a href=\"".$news_temp['guid']."\">".$news_temp['title']."</a></b></div><div>".$news_temp['description']."</div>";
								$html .= "<div>&nbsp;</div>";
							}	
							$html .= "</body></html>";
							if(!is_dir(storage_path('export_pdf/'.$giornale."/".$anno."/".$mese))){
								mkdir(storage_path('export_pdf/'.$giornale."/".$anno."/".$mese), 777, TRUE);
							}
							$path = storage_path('export_pdf/'.$giornale."/".$anno."/".$mese."/".$giorno.".pdf");
					        $html = preg_replace('/>\s+</', '><', $html);

					        $dompdf = new Dompdf();
							$dompdf->setPaper('A4', 'landscape');
							$dompdf->set_option('enable_html5_parser', FALSE);
							$dompdf->loadHtml($html);
							$dompdf->render();

							file_put_contents($path, $dompdf->output());
						}
					}
				}
			}
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
		public function updateNews($testata, $url){
			\Log::info("scarico le news");
			$response = Http::get($url);
			$xml = $response->body();
			$xml=simplexml_load_string($xml);
			$tot=0;
			foreach($xml->channel->item as $news){
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
					$tot++;
					//$this->writeToTelegramChannel(\strip_tags($n->title), $n->guid);
				}
			}
			\Log::info("download news completato");
			return $tot;
		}
		/*public function writeToTelegramChannel($title, $url){
			$this->telegram_config['text'] = "<a href=\"".$url."\">".$title."</a>";
			\Log::info("obj ".print_r($this->telegram_config, 1));
			$telegram = new Telegram(config('telegram.bot_token'), config('telegram.bot_name'));
			$telegram->handle();
			$result = Request::sendMessage($this->telegram_config);
			\Log::info(print_r($result, 1));
		}*/
		public function updateFavorites($favs){
			$id_user = Auth::user()->id;
			FavoriteSources::where(['id_user' => $id_user])->delete();
			$arr = [];
			foreach($favs as $f){
				array_push(
					$arr
					,[
						'id_user' => $id_user
						,'source' => $f
					]
				);				
			}
			$fw = new FavoriteSources();
			$fw->insert($arr);
		}
	}


 ?>