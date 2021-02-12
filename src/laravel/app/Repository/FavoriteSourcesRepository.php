<?php

    namespace App\Repository;

    use App\Models\FavoriteSources;
    use App\Models\News\NewsUser;

    class FavoriteSourcesRepository{
        public function __construct(){

        }
        public function getFavorites($id_user){
            $fsources = NewsUser::find($id_user)->favoriteSources()->get();
            return $fsources;
        }
    }