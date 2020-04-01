<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
        });

        Schema::create('drink_ingredient', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('ingredient_id');
            $table->unsignedBigInteger('drink_id');
            $table->string('measurement')->nullable();
            $table->string('unit');
            $table->unique(['ingredient_id', 'drink_id']);
            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('drink_ingredient');
    }
}
