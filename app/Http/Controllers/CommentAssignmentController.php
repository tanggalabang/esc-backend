<?php

namespace App\Http\Controllers;

use App\Models\CommentAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentAssignmentController extends Controller
{
  // public function index()
  // {
  //   $data = CommentAssignment::all();
  //   $user = User::all();

  //   return response()->json($data, 200);
  // }
  public function index()
  {
    $comments = CommentAssignment::all();
    $users = User::all();

    // Mengubah data user ke dalam bentuk associative array untuk mempermudah pencarian berdasarkan ID
    $usersData = $users->keyBy('id');

    // Memproses data komentar dan menambahkan nama user sesuai dengan user_id
    $mergedData = $comments->map(function ($comment) use ($usersData) {
      $user = $usersData->get($comment->user_id);
      return [
        'id' => $comment->id,
        'parent_id' => $comment->parent_id,
        'ass_uid' => $comment->ass_uid,
        'user_id' => $comment->user_id,
        'name' => $user->name,
        'profile_pic' => $user->profile_pic,
        'message' => $comment->message,
        'created_at' => $comment->created_at,
        'updated_at' => $comment->updated_at,
      ];
    });

    return response()->json($mergedData, 200);
  }

  public function create(Request $request)
  {

    $input = $request->all();

    $data = new CommentAssignment;
    $data->user_id = Auth::user()->id;
    $data->message = trim($request->message);
    $data->ass_uid = trim($request->ass_uid);
    $data->parent_id = trim($request->parent_id);
    $data->save();

    return response()->json($request, 200);
  }
}
