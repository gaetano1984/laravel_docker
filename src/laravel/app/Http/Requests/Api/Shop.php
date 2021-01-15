<?php

    namespace App\Http\Requests\Api;

    class Shop{
        public static $rules = [
            'ragione_sociale' => 'required'
            ,'indirizzo' => 'required'
            ,'stato' => 'required'
        ];
        public static function shopCreate(){
            return self::$rules;
        }
        public static function shopUpdate(){
            return self::$rules;
        }
    }