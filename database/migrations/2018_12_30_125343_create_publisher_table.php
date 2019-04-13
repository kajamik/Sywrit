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
            $table->bigIncrements('id');
            $table->string('nome')->unique();
            $table->text('componenti');
            $table->string('slug')->nullable();
            $table->string('logo')->nullable();
            $table->string('background')->nullable();
            $table->text('followers')->nullable();
            $table->integer('followers_count');
            $table->integer('direttore')->unique();
            $table->integer('avvisi');
            $table->enum('accesso', ['0','1']);
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
