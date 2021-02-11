<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

class News extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'news';
    public $timestamps = FALSE;

    public $autoIndex = false;

    protected $primaryKey = 'id_news';
    protected $fillable = ['title'];

    public function getScoutKey(){
        return $this->id_news;
    }

    public function getScoutKeyName(){
        return 'id_news';
    }

    public function searchableAs(){
        return 'news_index';
    }

    public function toSearchableArray(){
        return ['id_news' => $this->id_news, 'title' => $this->title, 'description' => $this->description];
    }
}
