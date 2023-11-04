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
    Schema::create('student_work', function (Blueprint $table) {
      $table->string('uid')->primary(); // Ini adalah primary key bertipe string
      $table->integer('student_id')->nullable();
      $table->string('ass_id')->nullable();
      $table->integer('class_id')->nullable();
      $table->longText('content');
      $table->integer('score')->nullable();
      $table->string('comment')->nullable();
      $table->tinyInteger('is_delete')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('student_work');
  }
};
