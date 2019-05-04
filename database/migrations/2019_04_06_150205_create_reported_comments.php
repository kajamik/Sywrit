<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportedComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_comments', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('user_id');
          $table->integer('comment_id');
          /*****************
            0 = Contenuto di natura sessuale
            1 = Contenuto violento o che incita all'odio
            2 = Contenuto discriminatorio
            3 = Promuove il terrorismo o attività criminali
            4 = Spam
          /*****************/
          $table->enum('report', ['0','1','2','3','4']);
          $table->text('report_text')->nullable();
          $table->string('report_token');
          $table->enum('resolved', ['0','1']);
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
        Schema::dropIfExists('reported_comments');
    }
}
