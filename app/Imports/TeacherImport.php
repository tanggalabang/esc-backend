<?php

namespace App\Imports;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeacherImport implements ToCollection, WithHeadingRow, WithValidation
{
  use Importable;

  protected $importedData = [];
  protected $importedEmails = [];

  public function collection(Collection $rows)
  {
    $password = Str::random(10);
    foreach ($rows as $row) {

      User::create([
        'name' => $row['name'],
        'email' => $row['email'],
        'password' => Hash::make($password),
        'user_type' => 2,
      ]);
    }

    // Tambahkan data yang diimpor ke daftar
    $this->importedData[] = [
      'name' => $row['name'],
      'email' => $row['email'],
      'password' => $password,
    ];

    // Tambahkan email yang diimpor ke daftar
    $this->importedEmails[] = $row['email'];
  }

  public function rules(): array
  {
    return [
      'name' => 'required',
      'email' => 'required|email|unique:users,email,NULL,id,is_delete,0',
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
