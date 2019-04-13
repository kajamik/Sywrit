<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_users', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('user_id');
          /*****************
          0 = Profilo falso
          1 = Contenuto violento o che incita all'odio
          2 = Promuove il terrorismo o attività criminali
          3 = Notizia Falsa ( Fake News )
          4 = Violazione del diritto d'autore
          5 = Spam
          /*****************/
          $table->enum('report', ['0','1','2','3','4','5']);
          $table->string('report_token');
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
        Schema::dropIfExists('reported_users');
    }
}
