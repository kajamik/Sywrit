<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articoli', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titolo');
            $table->string('tags')->nullable();
            $table->string('slug')->nullable();
            $table->text('testo');
            $table->string('copertina')->nullable();
            $table->integer('id_gruppo')->nullable(); // id editoria
            $table->integer('id_autore'); // id autore
            $table->integer('count_view');
            $table->integer('rating_count');
            $table->text('rated')->nullable();
            $table->enum('status',['0','1']); // no e si
            $table->timestamp('published_at');
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
        Schema::dropIfExists('articoli');
    }
}
