<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Jobs\ProsessSendEmail;
use App\Mail\AddStudentEmail;
use App\Models\ClassModel;
use App\Models\TimesTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use Str;


class ClassController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function getClassByTeacher()
  {
    // $teacherId = 2;
    $teacherId = Auth::user()->id;

    $timesTable = TimesTable::getTimesTable();

    $class = ClassModel::getClass();

    $result = [];
    $classIds = []; // Untuk melacak class_ids yang sudah ditambahkan ke hasil

    foreach ($timesTable as $item) {
      if ($item['teacher_id'] == $teacherId) {
        $classId = $item['class_id'];

        // Periksa apakah class_id sudah ada di hasil
        if (!in_array($classId, $classIds)) {
          foreach ($class as $classItem) {
            if ($classItem['id'] == $classId) {
              $result[] = $classItem;
              $classIds[] = $classId; // Tambahkan class_id ke array pelacakan
            }
          }
        }
      }
    }

    $result = collect($result)->sortBy('name')->values()->all();

    return response()->json($result, 200);
  }

  public function getClassByStudent()
  {
    // $teacherId = 2;
    // $teacherId = Auth::user()->id;

    $class = ClassModel::getClass()->find(Auth::user()->class_id);
    // $data = Assingment::find($request['uid']);

    return response()->json($class, 200);
  }
  public function index()
  {
    $users = ClassModel::getClass();

    return response()->json($users, 200);
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

    $nameArray = $request->all();
    foreach ($nameArray as $name) {
      $model = new ClassModel(); // Ganti YourModel dengan nama model Anda
      $model->name = $name['name'];
      $model->save();
    }


    return $this->sendResponse($model, "Class created succesfully");
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

    $class = ClassModel::find($id);
    $class->name = trim($request->name);
    $class->save();

    return $this->sendResponse($class, "Class updated succesfully");
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $student = ClassModel::find($id);
    $student->is_delete = 1;
    $student->save();
    return $this->sendResponse($student, "Class deleted succesfully");
  }

  public function import(Request $request)
  {
    try {
      $file = $request->file('file')->store('public/import');

      $import = new StudentImport;
      $import->import($file);

      $importedData = $import->getImportedData();
      $importedEmails = $import->getImportedEmails(); // Dapatkan array email dari StudentImport

      foreach ($importedData as $data) {
        $nis = $data['nis'];
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        // Mail::to($email)->send(new AddStudentEmail($nis, $name, $email, $password));
        ProsessSendEmail::dispatch($nis, $name, $email, $password);
        // Lakukan sesuatu dengan data ini, seperti menyimpannya ke database atau melakukan operasi lainnya.
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
