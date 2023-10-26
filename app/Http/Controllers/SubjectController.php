<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;


class SubjectController extends Controller
{
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
