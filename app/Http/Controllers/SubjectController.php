<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;
use App\Models\TimesTable;


class SubjectController extends Controller
{
  public function getSubjectByTeacher()
  {
    // $teacherId = 23;
    $teacherId = Auth::user()->id;

    $timesTable = TimesTable::getTimesTable();

    // $class = ClassModel::getClass();
    $subject = Subject::getSubject();

    $result = [];
    // $classIds = []; // Untuk melacak class_ids yang sudah ditambahkan ke hasil
    $subjectIds = []; // Untuk melacak class_ids yang sudah ditambahkan ke hasil

    foreach ($timesTable as $item) {
      if ($item['teacher_id'] == $teacherId) {
        $subjectId = $item['subject_id'];

        // Periksa apakah class_id sudah ada di hasil
        if (!in_array($subjectId, $subjectIds)) {
          foreach ($subject as $subjectItem) {
            if ($subjectItem['id'] == $subjectId) {
              $result[] = $subjectItem;
              $subjectIds[] = $subjectId; // Tambahkan class_id ke array pelacakan
            }
          }
        }
      }
    }

    return response()->json($result, 200);
  }
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $data = Subject::getSubject();

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
    $nameArray = $request->all();
    foreach ($nameArray as $name) {
      $data = new Subject(); // Ganti YourModel dengan nama model Anda
      $data->name = $name['name'];
      $data->save();
    }


    return $this->sendResponse($data, "Class created succesfully");
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
    $data = Subject::find($id);
    $data->name = trim($request->name);
    $data->save();

    return $this->sendResponse($data, "subject updated succesfully");
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $data = Subject::find($id);
    $data->is_delete = 1;
    $data->save();
    return $this->sendResponse($data, "Subject deleted succesfully");
  }
}
