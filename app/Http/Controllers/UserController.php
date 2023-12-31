<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Str;

class UserController extends Controller
{
  public function changePassword(Request $request)
  {
    $input = $request->all();

    $validator = Validator::make($input, [
      "newPassword" => "required|min:8"
    ]);

    if ($validator->fails()) {
      return $this->sendError("The new password field must be at least 8 characters", $validator->errors());
    }

    $user = User::getSingle(Auth::user()->id);

    if (Hash::check($request->oldPassword, $user->password)) {
      $user->password = Hash::make($request->newPassword);
      $user->save();

      return $this->sendResponse("success", 200);
    } else {

      return response()->json(['message' => 'Incorect old password'], 500);
    }

    // return response()->json($user, 200);
  }
  public function update(Request $request)
  {
    // if ($request->hasFile('file')) {
    //   $uploadedFiles = $request->file('file');
    //   $fileCount = count($uploadedFiles);

    //   if ($fileCount > 0) {
    //     return "Jumlah file yang terkirim: " . $fileCount;
    //   } else {
    //     return "Tidak ada file yang terkirim.";
    //   }
    // } else {
    //   return "Tidak ada file yang terkirim.";
    // }
    $input = $request->all();

    $validator = Validator::make($input, [
      'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan format yang Anda butuhkan
    ]);

    if ($validator->fails()) {
      return $this->sendError("Format is not image", $validator->errors());
    }

    $request->validate([]);
    $user = User::getSingle(Auth::user()->id);
    if (!empty($request->name)) {
      $user->name = trim($request->name);
    }
    if (!empty($request->file('image'))) {
      $ext = $request->file('image')->getClientOriginalExtension();
      $file = $request->file('image');
      $randomStr = date('Ymdhis') . Str::random(20);
      $filename = 'upload/profile/' . strtolower($randomStr) . '.' . $ext;
      $file->move('upload/profile/', $filename);

      $user->profile_pic = $filename;
    }
    $user->save();

    return response($request, 200);
  }
  public function deletePic()
  {
    $user = User::getSingle(Auth::user()->id);
    $user->profile_pic = null;
    $user->save();

    return response($user, 200);
    // $input = $request->all();

    // var_dump($input);
    // return response()->json($input);
  }
}
