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
    Schema::create('times', function (Blueprint $table) {
      $table->id();
      $table->string('day');
      $table->string('place')->nullable();
      $table->string('number_period');
      $table->integer('teacher_id')->nullable();
      $table->integer('class_id')->nullable();
      $table->integer('subject_id')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('times');
  }
};
