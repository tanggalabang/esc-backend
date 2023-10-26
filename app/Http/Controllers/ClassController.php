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


class ClassController extends Controller
{
  /**
   * Display a listing of the resource.
   */
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
