<?php 

	namespace App\Services;

	use Dompdf\Dompdf;
	use App\Models\News;
	use App\Mail\SendNewsAttach;
	use App\Models\News\NewsUser;
	use App\Models\FavoriteSources;
	/*use Longman\TelegramBot\Request;
	use Longman\TelegramBot\Telegram;*/
	use App\Repository\NewsRepository;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Http;
	use Illuminate\Support\Facades\File;
	use Illuminate\FileSystem\FileSystem;
	use Illuminate\Support\Facades\Storage;
	use App\Repository\FavoriteSourcesRepository;

	class NewsService{
		public $telegram_config;
		public $newsRepository;
		public $favoriteSourcesRepository;
		public function __construct(NewsRepository $newsRepository, FavoriteSourcesRepository $favoriteSourcesRepository){
			/*$this->telegram_config = [
				'chat_id' => config('telegram.chat_id')
				,'text' => ''
			];*/
			$this->newsRepository = $newsRepository;
			$this->favoriteSourcesRepository = $favoriteSourcesRepository;
		}
		public function removeDuplicate(){
			$this->newsRepository->removeDuplicate();
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
			$fsources = $this->favoriteSourcesRepository->getFavorites($id_user);
			return $fsources;
		}
		public function paginateNews($id_user, $n, $giornale){
			$favs = $this->favoriteSourcesRepository->getFavorites($id_user)->toArray();
			$news = $this->filterNews($favs, $n, $giornale);
			return $news;
		}
		public function filterNews($favs, $n, $giornale=null){
			$f_arr = [];
			if($giornale!=null){
				$f_arr = explode(',', $giornale);
			}
			else{
				foreach($favs as $f){
					array_push($f_arr, $f['source']);
				}
			}			
			$news = $this->newsRepository->filterNews($n, $f_arr);
			return $news;
		}
		public function exportToPdf(){
			$news = $this->newsRepository->exportToPdf();

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
							Storage::disk('ftp')->put(
								'export_pdf/'.$giornale."/".$anno."/".$mese."/".$giorno.".pdf", 
								$path
							);	
						}
					}
				}
			}
			$f = new FileSystem();
			$f->cleanDirectory(storage_path('export_pdf'));
		}
		public function readNews($obj){
			$news = $this->newsRepository->readNwes($obj);
			return $news;
		}
		public function updateNews($testata, $url){
			\Log::info("scarico le news");
			$response = Http::get($url);
			$xml = $response->body();
			$xml=simplexml_load_string($xml);
			$tot=0;
			foreach($xml->channel->item as $news){
				$tot += $this->newsRepository->updateNews($testata, $news);
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