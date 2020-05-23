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
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('filename');
            $table->string('url');
            $table->unsignedBigInteger('drink_id')->nullable();
            $table->timestamps();
        });

        // Schema::table('drinks', function($table) {
        //     $table->unsignedBigInteger('image')->nullable();
        //     $table->foreign('image')->references('id')->on('images')->onDelete('cascade');
        // });
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
