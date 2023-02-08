<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('members', function (Blueprint $table) {
      $table->id();
      $table->integer('status');
      $table->string('username');
      $table->string('password');
      $table->string('email');
      $table->string('name');
      $table->string('city')->nullable();
      $table->string('country')->nullable();
      $table->integer('sent_mails_this_month')->nullable();
      $table->integer('sent_mails_total')->nullable();
      $table->string('expires');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('members');
  }

};
