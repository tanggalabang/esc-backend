<?php

use App\Http\Controllers\ClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TimesTableController;

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
  Route::get('/user', function (Request $request) {
    return $request->user();
  });

  Route::resource("students", StudentController::class);
  Route::resource("teacher", TeacherController::class);
  Route::resource("class", ClassController::class);
  Route::resource("subject", SubjectController::class);
  Route::post('student-excel', [StudentController::class, 'import']);
  Route::post('times-table', [TimesTableController::class, 'store']);
  Route::get('times-table/{classId}', [TimesTableController::class, 'show']);
  Route::get('template-excel', [StudentController::class, 'templateExcel']);
});
