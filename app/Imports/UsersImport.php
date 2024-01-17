<?php

namespace App\Imports;

use App\Models\FailedRow;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, ShouldQueue, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        // validate rows
        if($row[0] == null || $row[1] == null || $row[2] == null || $row[3] == null){
            return null;
        }

        // check if user exists
        $user = User::where('identity_number', $row[1])->first();
        if($user){
            FailedRow::query()->create([
                'reason' => 'Bu kimlik numarası ile kayıtlı kullanıcı zaten var',
                'value' => $row[1] . " ". $row[2],
                'row_number' => 0
            ]);
            return null;
        }

        $user = User::where('phone_number', $row[3])->first();

        if($user){
            FailedRow::query()->create([
                'reason' => 'Bu telefon numarası ile kayıtlı kullanıcı zaten var',
                'value' => $row[3] . " ". $row[2],
                'row_number' => 0
            ]);
            return null;
        }

        // validate phone number without 0 at the beginning
        if(strlen($row[3]) != 10){
            FailedRow::query()->create([
                'reason' => 'Telefon numarası başında 0 olmadan 10 haneli olmalıdır.',
                'value' => $row[3]. " ". $row[2],
                'row_number' => 0
            ]);
            return null;
        }

        return new User([
              'name'     => $row[0],
              'identity_number'    => $row[1],
              'email' => $row[2],
              'phone_number' => $row[3],
              'role' => 'USER',
              'password' => Hash::make(12121221128997645),
              'sms_verified_at' => null,
              'can_do_reservation' =>true
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
