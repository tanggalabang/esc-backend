<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\CommentAssignmentController;
use App\Http\Controllers\MaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentWorkController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TimesTableController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
  //authentication
  Route::get('/user', function (Request $request) {
    return $request->user();
  });
  //class subject
  Route::resource("class", ClassController::class);
  Route::resource("subject", SubjectController::class);
  //student
  Route::resource("students", StudentController::class);
  Route::post('student-excel', [StudentController::class, 'import']);
  Route::get('template-excel', [StudentController::class, 'templateExcel']);
  //teacher
  Route::resource("teacher", TeacherController::class);
  Route::post('teacher-excel', [TeacherController::class, 'import']);
  //times table
  Route::post('times-table', [TimesTableController::class, 'store']);
  Route::get('times-table/{classId}', [TimesTableController::class, 'show']);
  //profile
  Route::put('change-password', [UserController::class, 'changePassword']);
  Route::post('update-profile', [UserController::class, 'update']);
  Route::post('delete-pic', [UserController::class, 'deletePic']);

  //TEACHER
  //assignment
  Route::resource("teacher-assignment", AssignmentController::class);
  Route::post("teacher-assignment-edit", [AssignmentController::class, 'storeAss']);
  //material
  Route::post("teacher-material-edit", [MaterialController::class, 'storeAss']);
  //file upload ass mat
  Route::post('files/{uid}', [AssignmentController::class, 'add']);
  Route::get('files', [AssignmentController::class, 'get']);
  Route::post('files-edit/{uid}', [AssignmentController::class, 'updateAss']);
  //comment ass mat
  Route::post('comment-assignment', [CommentAssignmentController::class, 'create']);
  //times table

  //TEACHER
});
Route::resource("teacher-material", MaterialController::class);

Route::get('times-table', [TimesTableController::class, 'index']);
Route::get('times-table-teacher-class', [TimesTableController::class, 'tttc']);

//get class subject by Teacher
Route::get('class-teacher', [ClassController::class, 'getClassByTeacher']);
Route::get('subject-teacher', [SubjectController::class, 'getSubjectByTeacher']);

//get material by Teacher
Route::get("material-student", [MaterialController::class, 'getMaterialByStudent']);
Route::get('material-teacher', [MaterialController::class, 'getMaterialByTeacher']);
Route::get("assignment-student", [AssignmentController::class, 'getAssignmentByStudent']);
Route::get('assignment-teacher', [AssignmentController::class, 'getAssignmentByTeacher']);

//student work
Route::post('student-work', [StudentWorkController::class, 'store']);
Route::get('student-work', [StudentWorkController::class, 'getWorkByStudent']);
Route::put('student-work/{id}', [StudentWorkController::class, 'addUpdate']);

//student
Route::get('student-with-work', [StudentController::class, 'getStudentWithWork']);

//comment
Route::get('comment-assignment', [CommentAssignmentController::class, 'index']);
