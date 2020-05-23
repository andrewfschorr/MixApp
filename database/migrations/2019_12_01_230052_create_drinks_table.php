<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drinks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('glass_type')->nullable();
            $table->timestamps();
            $table->unsignedSmallInteger('approved_status')->default(config('enums.approvedStatus.APPROVED'));
            $table->json('instructions')->nullable(); // this probably shouldn't be nullable
            // foreign keys
            $table->unsignedBigInteger('added_by');
            $table->foreign('added_by')->references('id')->on('users');
        });

        Schema::create('drink_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('drink_id');
            $table->decimal('rating', 2, 1)->nullable();
            $table->timestamps();
            $table->unique(['drink_id', 'user_id']);
            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drink_user');
        Schema::dropIfExists('drinks');
    }
}
