<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublisherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editori', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->unique();
            $table->text('componenti');
            $table->string('logo');
            $table->integer('direttore')->unique();
            $table->integer('avvisi');
            $table->integer('accesso');
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
        Schema::dropIfExists('editori');
    }
}
