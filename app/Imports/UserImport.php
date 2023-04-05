<?php

namespace App\Imports;

use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel ,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        Validator::make($row,[
            'first_name'            =>  'required|string|min:3|max:30',
            'last_name'             =>  'required|string|min:3|max:30',
            'email'                 =>  'required|email|unique:users,email',
            'phone'                 =>  'required|numeric|unique:users,phone',
        ])->validate();
        
        return new user([
            "id"                => $row["id"],
            "first_name"        => $row["first_name"],
            "last_name"         => $row["last_name"],
            "email"             => $row["email"],
            "phone"             => $row["phone"],
            "password"          => Hash::make(12345678),
            'email_verified_at' => Carbon::now(),
            "birthdate"         => $row["birthdate"],
            "is_active"         => $row["is_active"],
        ]);
    }
}
