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
            $table->text('testo');
            $table->string('copertina');
            $table->integer('id_gruppo')->nullable(); // id editoria
            $table->integer('autore'); // id autore
            $table->enum('pubblicato',['0','1']); // no e si
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
