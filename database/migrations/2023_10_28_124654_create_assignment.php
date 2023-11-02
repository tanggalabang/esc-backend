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
    Schema::create('assignment', function (Blueprint $table) {
      $table->string('uid')->primary(); // Ini adalah primary key bertipe string
      $table->string('name');
      $table->integer('class_id')->nullable();
      $table->integer('subject_id')->nullable();
      $table->integer('created_by')->nullable();
      $table->dateTime('due_date');
      $table->longText('content');
      $table->tinyInteger('is_delete')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('assignment');
  }
};
