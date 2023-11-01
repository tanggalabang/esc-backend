<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\TimesTable;
use App\Models\User;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Time;
use Symfony\Component\VarDumper\VarDumper;

class TimesTableController extends Controller
{
  //
  // public function index()
  // {
  //   $timesTable = TimesTable::all();
  //   $class = ClassModel::getClass();

  //   return response()->json($class);
  // }
  public function tttc()
  {
    $timesTable = TimesTable::all();
    $class = ClassModel::getClass();
    $teacher = User::getTeacher();

    return response()->json($teacher);
  }
  public function indexClassTeacher()
  {
    $timesTable = TimesTable::all();
    $class = ClassModel::getClass();

    $classMap = []; // Membuat array untuk melacak kelas yang sudah ditemukan
    $result = []; // Array untuk menyimpan hasil akhir

    // Loop through $timesTable and add "class_name" field
    foreach ($timesTable as $timetable) {
      $classId = $timetable->class_id;

      // Cek apakah kelas dengan id yang sama sudah ada dalam $classMap
      if (!isset($classMap[$classId])) {
        // Cari kelas yang sesuai dengan id atau nama
        $classInfo = collect($class)->first(function ($item) use ($classId, $timetable) {
          return $item['id'] == $classId || $item['name'] == $timetable->class_name;
        });

        if ($classInfo) {
          $timetable->class_name = $classInfo['name'];
          $classMap[$classId] = true; // Tandai kelas ini sudah ditemukan
          $result[] = $timetable; // Tambahkan entri ke hasil akhir
        }
      }
    }

    return response()->json($result);
  }
  public function store(Request $request)
  {

    $classData = $request->all();
    $class_id = ClassModel::where('name', $classData['class'])->first()->id;

    TimesTable::where('class_id', $class_id)->delete();

    $teachers = User::where('user_type', 2)->get();


    foreach ($classData['periods'] as $period) {
      $timesTable = new TimesTable;
      $timesTable->day = $period['day'];
      $timesTable->place = $period['place'];
      $timesTable->number_period = $period['number'];
      $timesTable->class_id = ClassModel::where('name', $classData['class'])->first()->id;
      $timesTable->subject_id = Subject::where('name', $period['subject'])->first()->id;
      // Mengambil ID guru (teacher) dari data yang sesuai dalam 'user' berdasarkan 'type_user'
      $teacher = $teachers->where('name', $period['teacher'])->first();
      if ($teacher) {
        $timesTable->teacher_id = $teacher->id;
      }
      $timesTable->save();
    }

    return $this->sendResponse("success", 200);
  }

  public function show($classId)
  // public function show()
  {
    // $classId = 1;
    // Gantilah 'classId' dengan parameter yang sesuai dengan rute Anda

    // Anda dapat mengambil data dari tabel 'times_table' berdasarkan class_id
    $timesTableData = TimesTable::where('class_id', $classId)->get();

    $teachers = User::where('user_type', 2)->get();


    $classData = [
      'class' => '', // Gantilah dengan nama kelas yang sesuai
      'periods' => [],
    ];

    foreach ($timesTableData as $item) {



      $classData['class'] = ClassModel::where('id', $item->class_id)->first()->name; // Mengambil nama kelas dari relasi
      $teachers = User::where('user_type', 2)->get();
      $classData['periods'][] = [
        'day' => $item->day,
        'number' => $item->number_period,
        'subject' => Subject::where('id', $item->subject_id)->first()->name, // Mengambil nama subjek dari relasi
        'teacher' => $teachers->where('id', $item->teacher_id)->first()->name,
        'place' => $item->place, // Mengambil nama guru dari relasi
      ];
    }

    return response()->json($classData, 200);
  }
}
