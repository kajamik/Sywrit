<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->integer('id_user'); // id utente di chi apre il ticket
          $table->enum('status', ['0','1']); // 0 aperto 1 chiuso
          $table->string('slug');
          $table->timestamps();
          $table->timestamp('last_msg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket');
    }
}
