<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Http\Request;
use Validator;
use App\Models\File;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Str;


class MaterialController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $material = Material::getMaterial();
    $classes = ClassModel::getClass();
    $subjects = Subject::getSubject();

    // Buat array asosiatif untuk menghubungkan class_id ke nama class
    $classMap = [];
    foreach ($classes as $class) {
      $classMap[$class['id']] = $class['name'];
    }

    // Buat array asosiatif untuk menghubungkan subject_id ke nama subject
    $subjectMap = [];
    foreach ($subjects as $subject) {
      $subjectMap[$subject['id']] = $subject['name'];
    }

    // Gabungkan data dari $assignment dengan nama class dan subject
    $mergedAssignments = [];
    foreach ($material as $assignment) {
      $mergedAssignment = [
        'uid' => $assignment['uid'],
        'name' => $assignment['name'],
        'thumbnail' => $assignment['thumbnail'],
        'class_id' => $assignment['class_id'],
        'subject_id' => $assignment['subject_id'],
        'class_name' => $classMap[$assignment['class_id']],
        'subject_name' => $subjectMap[$assignment['subject_id']],
        'due_date' => $assignment['due_date'],
        'content' => $assignment['content'],
        'is_delete' => $assignment['is_delete'],
        'created_at' => $assignment['created_at'],
        'updated_at' => $assignment['updated_at'],
      ];

      $mergedAssignments[] = $mergedAssignment;
    }

    return response()->json($mergedAssignments, 200);
  }

  public function getMaterialByTeacher()
  {
    $material = Material::getMaterial();
    $classes = ClassModel::getClass();
    $subjects = Subject::getSubject();

    // Deklarasikan class_id yang ingin Anda filter berdasarkan user yang login
    $selectedCreatedById = Auth::user()->id;
    // $selectedCreatedById = 3;

    // Buat array asosiatif untuk menghubungkan class_id ke nama class
    $classMap = [];
    foreach ($classes as $class) {
      $classMap[$class['id']] = $class['name'];
    }

    // Buat array asosiatif untuk menghubungkan subject_id ke nama subject
    $subjectMap = [];
    foreach ($subjects as $subject) {
      $subjectMap[$subject['id']] = $subject['name'];
    }

    // Gabungkan data dari $assignment dengan nama class dan subject
    $mergedAssignments = [];
    foreach ($material as $assignment) {
      if ($assignment['created_by'] == $selectedCreatedById) {
        $mergedAssignment = [
          'uid' => $assignment['uid'],
          'name' => $assignment['name'],
          'thumbnail' => $assignment['thumbnail'],
          'class_id' => $assignment['class_id'],
          'subject_id' => $assignment['subject_id'],
          'class_name' => $classMap[$assignment['class_id']],
          'subject_name' => $subjectMap[$assignment['subject_id']],
          'due_date' => $assignment['due_date'],
          'content' => $assignment['content'],
          'is_delete' => $assignment['is_delete'],
          'created_at' => $assignment['created_at'],
          'updated_at' => $assignment['updated_at'],
        ];

        $mergedAssignments[] = $mergedAssignment;
      }
    }

    return response()->json($mergedAssignments, 200);
  }
  public function getMaterialByStudent()
  {
    $material = Material::getMaterial();
    $classes = ClassModel::getClass();
    $subjects = Subject::getSubject();

    // Deklarasikan class_id yang ingin Anda filter berdasarkan user yang login
    $selectedClassId = Auth::user()->class_id;
    // $selectedClassId = 2;

    // Buat array asosiatif untuk menghubungkan class_id ke nama class
    $classMap = [];
    foreach ($classes as $class) {
      $classMap[$class['id']] = $class['name'];
    }

    // Buat array asosiatif untuk menghubungkan subject_id ke nama subject
    $subjectMap = [];
    foreach ($subjects as $subject) {
      $subjectMap[$subject['id']] = $subject['name'];
    }

    // Gabungkan data dari $assignment dengan nama class dan subject
    $mergedAssignments = [];
    foreach ($material as $assignment) {
      if ($assignment['class_id'] == $selectedClassId) {
        $mergedAssignment = [
          'uid' => $assignment['uid'],
          'name' => $assignment['name'],
          'thumbnail' => $assignment['thumbnail'],
          'class_id' => $assignment['class_id'],
          'subject_id' => $assignment['subject_id'],
          'class_name' => $classMap[$assignment['class_id']],
          'subject_name' => $subjectMap[$assignment['subject_id']],
          'due_date' => $assignment['due_date'],
          'content' => $assignment['content'],
          'is_delete' => $assignment['is_delete'],
          'created_at' => $assignment['created_at'],
          'updated_at' => $assignment['updated_at'],
        ];

        $mergedAssignments[] = $mergedAssignment;
      }
    }

    return response()->json($mergedAssignments, 200);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // $input = $request->all();
    try {
      $input = $request->all();

      $validator = Validator::make($input, [
        "name" => "required",
        "class" => "required",
        "subject" => "required",
        "content" => "required"
      ]);

      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      // return response()->json($input, 200);

      $data = new Material;
      $data->name = trim($request->name);
      $data->uid = trim($request->uid);
      $data->class_id = ClassModel::where('name', $request->class)->first()->id;
      $data->subject_id = Subject::where('name', $request->subject)->first()->id;
      $data->content = trim($request->content);
      $data->created_by = Auth::user()->id;
      if (!empty($request->file('image'))) {
        $ext = $request->file('image')->getClientOriginalExtension();
        $file = $request->file('image');
        $randomStr = date('Ymdhis') . Str::random(20);
        $filename = 'upload/material-thumb/' . strtolower($randomStr) . '.' . $ext;
        $file->move('upload/material-thumb/', $filename);

        $data->thumbnail = $filename;
      }
      $data->save();

      return $this->sendResponse($data, "Assignmet created succesfully");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
    // return response()->json($input, 200);
  }
  public function storeAss(Request $request)
  {
    // $input = $request->all();
    try {
      $input = $request->all();

      $validator = Validator::make($input, [
        "name" => "required",
        "class" => "required",
        "subject" => "required",
        "content" => "required"
      ]);

      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      // return response()->json($input, 200);

      $data = Material::where('uid', $request['uid'])->first();
      $data->name = trim($request->name);
      $data->uid = trim($request->uid);
      $data->class_id = ClassModel::where('name', $request->class)->first()->id;
      $data->subject_id = Subject::where('name', $request->subject)->first()->id;
      $data->content = trim($request->content);
      if (!empty($request->file('image'))) {
        $ext = $request->file('image')->getClientOriginalExtension();
        $file = $request->file('image');
        $randomStr = date('Ymdhis') . Str::random(20);
        $filename = 'upload/material-thumb/' . strtolower($randomStr) . '.' . $ext;
        $file->move('upload/material-thumb/', $filename);

        $data->thumbnail = $filename;
      }
      $data->save();

      return $this->sendResponse($data, "Assignmet created succesfully");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
    // return response()->json($input, 200);
  }
  // public function storeAss(Request $request)
  // {
  //   try {
  //     $input = $request->all();

  //     $validator = Validator::make($input, [
  //       "name" => "required",
  //       "class" => "required",
  //       "subject" => "required",
  //       "content" => "required"
  //     ]);

  //     if ($validator->fails()) {
  //       return $this->sendError("Validation Error", $validator->errors());
  //     }

  //     // $data = Assingment::find($request['uid']);
  //     $data = Material::where('uid', $request['uid'])->first();
  //     $data->name = trim($request->name);
  //     $data->class_id = ClassModel::where('name', $request->class)->first()->id;
  //     $data->subject_id = Subject::where('name', $request->subject)->first()->id;
  //     $data->content = trim($request->content);
  //     $data->save();

  //     return $this->sendResponse($data, "Assignmet created succesfully");
  //   } catch (\Exception $e) {
  //     return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
  //   }
  // }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $student = Material::find($id);
    $student->is_delete = 1;
    $student->save();
    return $this->sendResponse($student, "Product deleted succesfully");
  }


  public function updateAss(Request $request, $id)
  {
    try {
      $files = [];
      if ($request->file('files')) {
        File::where('ass_uid', $id)->delete();

        foreach ($request->file('files') as $key => $file) {
          $file_name = time() . rand(1, 99);
          $file->move(public_path('uploads'), $file_name . '-' . $file->getClientOriginalName());
          $files[] = [
            'ass_uid' => $id, // Tambahkan UID ke array file
            'name' => 'uploads/' . $file_name . '-' . $file->getClientOriginalName(),

          ];
        }
        foreach ($files as $key => $file) {
          File::create($file);
        }
        return $this->sendResponse($files, "Files created succesfully");
      }
      return $this->sendResponse($files, "Files empty not edit");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
    // return response()->json($id);
  }
  public function add(Request $request, $id)
  {
    try {
      $request->validate([
        'files' => 'required',
      ]);

      $files = [];
      if ($request->file('files')) {
        foreach ($request->file('files') as $key => $file) {
          $file_name = time() . rand(1, 99);
          $file->move(public_path('uploads'), $file_name . '-' . $file->getClientOriginalName());
          $files[] = [
            'ass_uid' => $id, // Tambahkan UID ke array file
            'name' => 'uploads/' . $file_name . '-' . $file->getClientOriginalName(),

          ];
        }
      }

      foreach ($files as $key => $file) {
        File::create($file);
      }

      return $this->sendResponse($files, "Files created succesfully");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
    // return response()->json($id);
  }
  public function get()
  {
    $data = File::all();

    return response()->json($data, 200);
  }
}
