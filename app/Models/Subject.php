<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
  use HasFactory;
  protected $table = 'subject';
  static public function getSubject()
  {
    $return = self::select('subject.*')
      ->where('subject.is_delete', '=', 0);
    $return = $return->orderBy('subject.id', 'desc')
      ->get();
    return $return;
  }
}
