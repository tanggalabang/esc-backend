<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Jobs\ProsessSendEmail;
use App\Mail\AddStudentEmail;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use Str;


class StudentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $class = ClassModel::getClass();
    $users = User::getStudent();

    $data = [];

    foreach ($users as $user) {
      $userData = $user;

      // Cari kelas yang sesuai dengan class_id dari pengguna
      $classData = collect($class)->where('id', $user['class_id'])->first();
      if ($classData) {
        $userData['class'] = $classData['name'];
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

    $class = ClassModel::where('name', $request['class'])->first();

    $password = Str::random(10);
    $student = new User;
    $student->nis = trim($request->nis);
    $student->name = trim($request->name);
    $student->email = trim($request->email);
    $student->class_id = $class['id'];
    $student->password = Hash::make($password);
    $student->user_type = 3;
    $student->save();


    $emailTo = $request->email;
    Mail::to($emailTo)->send(new AddStudentEmail($student->nis, $student->name, $student->email, $password));

    return $this->sendResponse($student, "Product created succesfully");
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

      $import = new StudentImport;
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
    } catch (\Exception $e) {
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
