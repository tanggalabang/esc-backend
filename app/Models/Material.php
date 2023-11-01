<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  use HasFactory;
  protected $table = 'material';

  protected $primaryKey = 'uid';
  protected $keyType = 'string';


  protected $fillable = [
    'uid',
    'name',
  ];
  static public function getMaterial()
  {
    $return = self::select('material.*')
      ->where('material.is_delete', '=', 0);
    $return = $return->orderBy('material.uid', 'desc')
      ->get();
    return $return;
  }
}
