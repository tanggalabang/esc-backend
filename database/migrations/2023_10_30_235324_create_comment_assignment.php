<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('comment_assignment', function (Blueprint $table) {
      $table->id();
      $table->integer('parent_id'); //parent of comment
      $table->string('ass_uid');
      $table->integer('user_id');
      $table->text('message');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('comment_assignment');
  }
};
