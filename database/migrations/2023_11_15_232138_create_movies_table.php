<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->string('imdbID')->primary();
            $table->string('title');
            $table->string('type');
            $table->date('releasedDate');
            $table->string('year');
            $table->string('posterUrl');
            $table->string('genre');
            $table->string('runtime');
            $table->string('country');
            $table->string('imdbRating');
            $table->string('imdbVotes');
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
        Schema::dropIfExists('movies');
    }
};
