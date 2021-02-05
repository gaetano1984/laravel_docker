<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class News extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('news', function($table){
            $table->increments('id_news');
            $table->char('title', 255);
            $table->timestamp('pubDate');
            $table->text('guid');
            $table->text('description');
            $table->char('category', 255);
            $table->text('giornale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('news');
    }
}
