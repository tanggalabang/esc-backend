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
use Str;


class AssignmentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $data = Assingment::getAssignment();
    // $data = Assingment::all();

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
    $data->due_date = trim($request->dueDate);
    $data->content = trim($request->content);
    $data->save();

    return $this->sendResponse($data, "Assignmet created succesfully");
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

  public function add(Request $request, $id)
  {
    try {
      $request->validate([
        'files' => 'required',
      ]);

      $files = [];
      if ($request->file('files')) {
        foreach ($request->file('files') as $key => $file) {
          // $file_name = time() . rand(1, 99) . '.' . $file->extension();
          $file_name = time() . rand(1, 99);
          $file->move(public_path('uploads'), $file_name . '-' . $file->getClientOriginalName());
          // $files[]['name'] = 'uploads/' . $file_name;
          $files[] = [
            'ass_uid' => $id, // Tambahkan UID ke array file
            // 'name' => 'uploads/' . $file_name,
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
