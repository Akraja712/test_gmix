<?php

namespace App\Imports;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Users([
            'name'       => $row['name'],
            'email'      => $row['email'],
            'mobile'     => $row['mobile'],
            'age'        => $row['age'],
            'gender'     => $row['gender'],
            'profession' => $row['profession'],
            'points'     => $row['points'],
        ]);
    }
}
