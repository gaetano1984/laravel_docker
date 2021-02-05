<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FavoriteSources;

class NewsUser extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    public function favoriteSources(){
    	return $this->hasMany(FavoriteSources::class, 'id_user');
    }
}
