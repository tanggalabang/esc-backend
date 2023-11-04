<?php

namespace App\Http\Controllers;

use App\Imports\TeacherImport;
use App\Jobs\ProsessSendEmail;
use App\Models\Assingment;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Str;


class AssignmentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $assignments = Assingment::getAssignment();
    $classes = ClassModel::getClass();
    $subjects = Subject::getSubject();
    $users = User::getTeacher();

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

    // Buat array asosiatif untuk menghubungkan subject_id ke nama subject
    $teacherMap = [];
    foreach ($users as $user) {
      $teacherMap[$user['id']] = $user['name'];
    }

    // Gabungkan data dari $assignment dengan nama class dan subject
    $mergedAssignments = [];
    foreach ($assignments as $assignment) {
      $mergedAssignment = [
        'uid' => $assignment['uid'],
        'name' => $assignment['name'],
        'class_id' => $assignment['class_id'],
        'subject_id' => $assignment['subject_id'],
        'class_name' => $classMap[$assignment['class_id']],
        'subject_name' => $subjectMap[$assignment['subject_id']],
        'due_date' => $assignment['due_date'],
        'content' => $assignment['content'],
        'autor' => $teacherMap[$assignment['created_by']],
        'is_delete' => $assignment['is_delete'],
        'created_at' => $assignment['created_at'],
        'updated_at' => $assignment['updated_at'],
      ];

      $mergedAssignments[] = $mergedAssignment;
    }

    return response()->json($mergedAssignments, 200);
  }
  public function getAssignmentByTeacher()
  {
    $assignments = Assingment::getAssignment();
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
    foreach ($assignments as $assignment) {
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
  public function getAssignmentByStudent()
  {
    $material = Assingment::getAssignment();
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
  public function storeAss(Request $request)
  {
    try {
      $input = $request->all();

      $validator = Validator::make($input, [
        "name" => "required",
        "class" => "required",
        "subject" => "required",
        "dueDate" => "required|date",
        "content" => "required"
      ]);

      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      // $data = Assingment::find($request['uid']);
      $data = Assingment::where('uid', $request['uid'])->first();
      $data->name = trim($request->name);
      $data->class_id = ClassModel::where('name', $request->class)->first()->id;
      $data->subject_id = Subject::where('name', $request->subject)->first()->id;
      $data->due_date = trim($request->dueDate);
      $data->content = trim($request->content);
      $data->save();

      return $this->sendResponse($data, "Assignmet created succesfully");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
  }
  public function store(Request $request)
  {
    try {
      $input = $request->all();

      $validator = Validator::make($input, [
        "name" => "required",
        "class" => "required",
        "subject" => "required",
        "dueDate" => "required|date",
        "content" => "required"
      ]);

      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      $data = new Assingment;
      $data->name = trim($request->name);
      $data->uid = trim($request->uid);
      $data->class_id = ClassModel::where('name', $request->class)->first()->id;
      $data->subject_id = Subject::where('name', $request->subject)->first()->id;
      $data->created_by = Auth::user()->id;
      $data->due_date = trim($request->dueDate);
      $data->content = trim($request->content);
      $data->save();

      return $this->sendResponse($data, "Assignmet created succesfully");
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
  }

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
    $input = $request->all();

    $validator = Validator::make($input, [
      "email" => "required|min:4"
    ]);

    if ($validator->fails()) {
      return $this->sendError("Validation Error", $validator->errors());
    }

    // $student= User::create($input);
    $student = User::find($id);
    $student->nis = trim($request->nis);
    $student->name = trim($request->name);
    $student->email = trim($request->email);
    $student->save();

    return $this->sendResponse($student, "Product updated succesfully");
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $student = Assingment::find($id);
    $student->is_delete = 1;
    $student->save();
    return $this->sendResponse($student, "Product deleted succesfully");
  }

  public function import(Request $request)
  {
    try {
      $file = $request->file('file')->store('public/import');

      $import = new TeacherImport;
      $import->import($file);

      $importedData = $import->getImportedData();
      $importedEmails = $import->getImportedEmails(); // Dapatkan array email dari StudentImport

      foreach ($importedData as $data) {
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        ProsessSendEmail::dispatch($name, $email, $password);
      }

      return response()->json([
        'message' => 'Data berhasil disimpan',
        'imported_data' => $importedData,
        'imported_emails' => $importedEmails, // Tambahkan data email ke respons JSON

      ], 200);

      // return response()->json(['message' => 'Data berhasil disimpan'], 200);
    } catch (\Exception $e) {
      // Handle other exceptions, e.g., file upload issues
      return response()->json(['message' => 'Error, duplicate email or nis. Please check the data', 'error' => $e->getMessage()], 500);
    }
  }
  public function templateExcel()
  {
    $path = public_path() . ('/files/example-excel.xlsx');

    if (file_exists($path)) {
      return response()->download($path);
    } else {
      abort(500);
    }
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
