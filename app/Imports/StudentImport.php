<?php

namespace App\Imports;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Str;

// class StudentImport implements ToModel, WithHeadingRow, WithValidation
// {
//   use Importable;

//   protected $importedData = [];
//   protected $importedEmails = [];

//   public function model(array $row)
//   {
//     $password = Str::random(10);
//     $user = new User([
//       'nis' => $row['nis'],
//       'name' => $row['name'],
//       'email' => $row['email'],
//       'password' => Hash::make($password),
//       'user_type' => 3,
//     ]);

//     // Tambahkan data yang diimpor ke daftar
//     $this->importedData[] = [
//       'nis' => $row['nis'],
//       'name' => $row['name'],
//       'email' => $row['email'],
//       'password' => $password,
//     ];

//     // Tambahkan email yang diimpor ke daftar
//     $this->importedEmails[] = $row['email'];

//     return $user;
//   }

//   public function rules(): array
//   {
//     return [
//       'nis' => 'required',
//       'name' => 'required',
//       'email' => 'required|email|unique:users,email,NULL,id,is_delete,0',
//     ];
//   }

//   public function getImportedData()
//   {
//     return $this->importedData;
//   }

//   public function getImportedEmails()
//   {
//     return $this->importedEmails;
//   }
// }

// class StudentImport implements ToCollection, WithHeadingRow, WithValidation
class StudentImport implements ToCollection, WithHeadingRow, WithValidation
{
  use Importable;

  protected $importedData = [];
  protected $importedEmails = [];

  public function collection(Collection $rows)
  {
    $password = Str::random(10);
    foreach ($rows as $row) {
      $class = ClassModel::where('name', $row['class'])->first();

      User::create([
        'nis' => $row['nis'],
        'name' => $row['name'],
        'email' => $row['email'],
        'class_id' => $class['id'],
        'password' => Hash::make($password),
        'user_type' => 3,
      ]);

      // Tambahkan data yang diimpor ke daftar
      $this->importedData[] = [
        'nis' => $row['nis'],
        'name' => $row['name'],
        'email' => $row['email'],
        'class_id' => $row['class'],
        'password' => $password,
      ];

      // Tambahkan email yang diimpor ke daftar
      $this->importedEmails[] = $row['email'];
    }
  }

  public function rules(): array
  {
    return [
      'nis' => 'required',
      'name' => 'required',
      'email' => 'required|email|unique:users,email,NULL,id,is_delete,0',
      'class' => 'required',
    ];
  }

  public function getImportedData()
  {
    return $this->importedData;
  }

  public function getImportedEmails()
  {
    return $this->importedEmails;
  }
}
