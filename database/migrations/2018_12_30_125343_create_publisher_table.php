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
            $table->string('name')->unique();
            $table->text('componenti');
            $table->string('slug')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover')->nullable();
            $table->text('biography')->nullable();
            $table->text('followers')->nullable();
            $table->integer('followers_count');
            $table->integer('direttore');
            $table->enum('suspended', ['0','1']);
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
