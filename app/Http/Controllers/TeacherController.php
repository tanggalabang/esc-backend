<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Jobs\ProsessSendEmail;
use App\Mail\AddStudentEmail;
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
    $users = User::getTeacher();

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
    // $teacher->email = trim($request->email);
    $teacher->password = Hash::make($password);
    $teacher->user_type = 2;
    $teacher->save();


    // $emailTo = $request->email;
    // Mail::to($emailTo)->send(new AddteacherEmail($teacher->nis, $teacher->name, $teacher->email, $password));

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
