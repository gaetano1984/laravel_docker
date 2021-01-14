<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops';

    public $timestamps = false;

    static function shopCreate(){
        return [
            'ragione_sociale' => 'required'
            ,'indirizzo' => 'required'
            ,'stato' => 'required'
        ];
    }
}
