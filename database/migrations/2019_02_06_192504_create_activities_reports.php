<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('article_id');
            /*****************
              0 = contenuto di natura sessuale
              1 = incita all'odio od offensivi
              2 = promuove il terrorismo
              3 = violazione del diritto d'autore
            /*****************/
            $table->enum('report', ['0','1','2','3']);
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
        Schema::dropIfExists('activities_reports');
    }
}
