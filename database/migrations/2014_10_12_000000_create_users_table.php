<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utenti', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('slug')->nullable();
            $table->string('avatar')->nullable();
            $table->string('copertina')->nullable();
            $table->string('biography')->nullable();
            // socials
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            //---
            $table->integer('rank');
            $table->float('points');
            //---
            $table->text('followers')->nullable();
            $table->integer('followers_count')->default(0);
            $table->integer('notifications_count')->default(0);
            $table->text('id_gruppo')->nullable();
            $table->enum('permission', ['1','2','3','4']);
            $table->enum('suspended', ['0','1']);
            $table->enum('cron', ['0','1']);
            $table->rememberToken();
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
        Schema::dropIfExists('utenti');
    }
}
