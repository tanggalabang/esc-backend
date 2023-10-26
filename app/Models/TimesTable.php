<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesTable extends Model
{
  use HasFactory;
  protected $table = 'times';
  static public function getTimesTable()
  {
    $return = self::select('times.*');
    $return = $return->orderBy('times.id', 'desc')
      ->get();
    return $return;
  }
}
