<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportedArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('article_id');
            /*****************
            0 = Contenuto di natura sessuale
            1 = Contenuto violento o che incita all'odio
            2 = Promuove il terrorismo o attività criminali
            3 = Notizia Falsa ( Fake News )
            4 = Violazione del diritto d'autore
            5 = Spam
            /*****************/
            $table->enum('report', ['0','1','2','3']);
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
        Schema::dropIfExists('activities_reports');
    }
}
