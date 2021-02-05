<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteSources extends Model
{
    use HasFactory;

    protected $table='favorite_sources';

    protected $fillable = ['id_user', 'source'];

    public $timestamps = FALSE;
}
