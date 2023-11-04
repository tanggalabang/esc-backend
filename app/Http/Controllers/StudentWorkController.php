<?php

namespace App\Http\Controllers;

use App\Imports\TeacherImport;
use App\Jobs\ProsessSendEmail;
use App\Models\Assingment;
use App\Models\ClassModel;
use App\Models\StudentWork;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Str;

class StudentWorkController extends Controller
{
  public function getWorkByStudent()
  {
    // $student = Auth::user()->id;
    $student = 4;

    $studentWorks = StudentWork::where("student_id", $student)
      ->where("is_delete", 0)
      ->get();


    return response()->json($studentWorks);
  }
  public function store(Request $request)
  {
    try {
      $input = $request->all();

      $validator = Validator::make($input, [
        "content" => "required"
      ]);

      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      $data = new StudentWork;
      $data->uid = trim($request->uid);
      $data->ass_id = trim($request->assId);
      $data->student_id = Auth::user()->id;
      $data->class_id = ClassModel::where('name', $request->class)->first()->id;
      $data->content = trim($request->content);
      $data->save();

      return $this->sendResponse($data, "Assignmet created succesfully");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
  }
  public function addUpdate(Request $request, string $id)
  {
    try {
      $input = $request->all();

      $validator = Validator::make($input, [
        "score" => "required"
      ]);

      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      $data = StudentWork::find($id);
      $data->score = trim($request->score);
      if ($request->comment) {

        $data->comment = trim($request->comment);
      }
      $data->save();

      return $this->sendResponse($data, "Assignmet created succesfully");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
  }
}
