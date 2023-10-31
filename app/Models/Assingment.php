<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assingment extends Model
{
  use HasFactory;
  protected $table = 'assignment';

  // protected $primaryKey = 'uid'; // Menentukan 'uid' sebagai primary key
  protected $primaryKey = 'uid'; // Menentukan 'uid' sebagai primary key
  protected $keyType = 'string'; // Menentukan tipe data 'uid'


  protected $fillable = [
    'uid',
    'name',
  ];
  static public function getAssignment()
  {
    $return = self::select('assignment.*')
      ->where('assignment.is_delete', '=', 0);
    $return = $return->orderBy('assignment.uid', 'desc')
      ->get();
    return $return;
  }
}
