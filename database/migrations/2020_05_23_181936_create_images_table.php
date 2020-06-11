<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // this is called drink_images because the model is DrinkImage, nothing to do with foreign key :/
        Schema::create('drink_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('filename');
            $table->string('url');
            // this needs to be nullable, i have no idea what it errors otherwise
            // https://laracasts.com/series/laravel-6-from-scratch/episodes/30#reply=4838
            $table->unsignedBigInteger('drink_id')->nullable();
            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
