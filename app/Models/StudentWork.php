<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentWork extends Model
{
  use HasFactory;
  protected $table = 'student_work';

  protected $primaryKey = 'uid'; // Menentukan 'uid' sebagai primary key
  protected $keyType = 'string'; // Menentukan tipe data 'uid'


  protected $fillable = [
    'uid',
    'name',
  ];
  static public function getStudentWork()
  {
    $return = self::select('student_work.*')
      ->where('student_work.is_delete', '=', 0);
    $return = $return->orderBy('student_work.uid', 'desc')
      ->get();
    return $return;
  }
}
