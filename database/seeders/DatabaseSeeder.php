<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    \App\Models\User::factory()->create([
      'name' => 'Admin',
      'email' => 'admin@email.com',
      'user_type' => 1,
      'password' => Hash::make(11111111),
    ]);
    //teacher
    \App\Models\User::factory()->create([
      'name' => 'Teacher',
      'email' => 'teacher@email.com',
      'user_type' => 2,
      'password' => Hash::make(11111111),
    ]);
    \App\Models\User::factory()->create([
      'name' => 'Teacher2',
      'email' => 'teacher2@email.com',
      'user_type' => 2,
      'password' => Hash::make(11111111),
    ]);
    //student
    \App\Models\User::factory()->create([
      'name' => 'Student',
      'email' => 'student@email.com',
      'class_id' => 1,
      'user_type' => 3,
      'password' => Hash::make(11111111),
    ]);
    \App\Models\User::factory()->create([
      'name' => 'Student',
      'email' => 'student@email.com',
      'class_id' => 2,
      'user_type' => 3,
      'password' => Hash::make(11111111),
    ]);

    //class and subject
    DB::table('class')->insert([
      'name' => 'RPL',
    ]);
    DB::table('class')->insert([
      'name' => 'TKJ',
    ]);
    DB::table('subject')->insert([
      'name' => 'Math',
    ]);
    DB::table('subject')->insert([
      'name' => 'Science',
    ]);
  }
}
