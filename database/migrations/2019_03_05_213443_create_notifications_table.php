<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('sender_id');
          $table->integer('target_id');
          $table->text('text');
          // 1 - Collaborazione
          /* Nuovo articolo
              2 - Utente
              3 - Pagina
          */
          $table->enum('type',['1','2','3']);
          $table->enum('marked', ['0','1']); // notifica letta
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
        Schema::dropIfExists('notifications');
    }
}
