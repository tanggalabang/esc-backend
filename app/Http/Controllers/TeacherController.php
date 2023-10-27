<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Imports\TeacherImport;
use App\Jobs\ProsessSendEmail;
use App\Mail\AddStudentEmail;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\TimesTable;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use Str;


class TeacherController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $class = ClassModel::getClass();
    $subject = Subject::getSubject();
    $tiTa = TimesTable::getTimesTable();
    $users = User::getTeacher();

    $data = [];

    foreach ($users as $user) {
      $userData = $user->toArray();

      // Inisialisasi array untuk menyimpan subjek dan kelas yang berhubungan dengan pengguna
      $userData['subject'] = [];
      $userData['classes'] = [];

      // Array tambahan untuk melacak subjek dan kelas yang sudah ditambahkan ke pengguna
      $addedSubjects = [];
      $addedClasses = [];

      // Cari subjek dan kelas yang berhubungan dengan pengguna dalam $tiTa
      foreach ($tiTa as $schedule) {
        if ($schedule['teacher_id'] == $user['id']) {
          // Temukan subjek yang sesuai
          $subjectData = collect($subject)->where('id', $schedule['subject_id'])->first();
          if ($subjectData && !in_array($subjectData['id'], $addedSubjects)) {
            $userData['subject'][] = $subjectData;
            $addedSubjects[] = $subjectData['id'];
          }

          // Temukan kelas yang sesuai
          $classData = collect($class)->where('id', $schedule['class_id'])->first();
          if ($classData && !in_array($classData['id'], $addedClasses)) {
            $userData['classes'][] = $classData;
            $addedClasses[] = $classData['id'];
          }
        }
      }

      $data[] = $userData;
    }


    return response()->json($data, 200);
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
    $input = $request->all();

    $validator = Validator::make($input, [
      "email" => "required|min:4"
    ]);

    if ($validator->fails()) {
      return $this->sendError("Validation Error", $validator->errors());
    }

    $password = Str::random(10);
    $teacher = new User;
    $teacher->name = trim($request->name);
    $teacher->email = trim($request->email);
    $teacher->password = Hash::make($password);
    $teacher->user_type = 2;
    $teacher->save();


    $emailTo = $request->email;
    Mail::to($emailTo)->send(new AddStudentEmail($teacher->name, $teacher->email, $password));

    return $this->sendResponse($teacher, "Teacher created succesfully");
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
    $student = User::find($id);
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
}
