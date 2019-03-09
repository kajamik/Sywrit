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
            $table->increments('id');
            $table->string('nome');
            $table->string('cognome');
            $table->string('email')->unique();
            $table->string('password');
            $table->datetime('birthday');
            $table->string('slug');
            $table->string('avatar')->nullable();
            $table->string('copertina')->nullable();
            $table->text('biography')->nullable();
            // socials
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            //---
            $table->text('followers')->nullable();
            $table->integer('followers_count');
            $table->integer('notifications_count');
            $table->enum('id_gruppo', ['0','1']);
            $table->enum('permission', ['0','1']);
            $table->enum('accesso', ['0','1']);
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
