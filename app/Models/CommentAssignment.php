<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentAssignment extends Model
{
  use HasFactory;
  protected $table = 'comment_assignment';
  protected $guarded = [
    'id'
  ];
}
