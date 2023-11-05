<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
  use HasFactory;
  protected $table = 'class';
  static public function getClass()
  {
    $return = self::select('class.*')
      ->where('class.is_delete', '=', 0);
    $return = $return->orderBy('class.id', 'asc')
      ->get();
    return $return;
  }
}
