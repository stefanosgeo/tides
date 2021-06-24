<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('owner_id')->references('id')->on('users');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('posterImage')->nullable();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('series_id')->nullable()->default(null);
            $table->integer('episode')->default('1');
            $table->boolean('allow_comments')->default(false);
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
        Schema::dropIfExists('clips');
    }
}
