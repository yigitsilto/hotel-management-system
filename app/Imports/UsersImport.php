<?php

namespace App\Imports;

use App\Models\FailedRow;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // validate rows
//        if($row['adsoyad'] == null || $row['tc'] == null || $row['eposta'] == null || $row['telefon'] == null){
//            return null;
//        }

        if ($row['eposta'] != null) {
            return;
        }

        if($row['adsoyad'] == null || $row['tc'] == null || $row['telefon'] == null){
            return null;
        }

        // check if user exists
        $user = User::where('identity_number', $row['tc'])->first();
        if($user){
            FailedRow::query()->create([
                'reason' => 'Bu kimlik numarası ile kayıtlı kullanıcı zaten var',
                'value' => $row['tc'] . " ". $row['eposta'],
                'row_number' => 0
            ]);
            return null;
        }



        //$user = User::where('email', $row['eposta'])->first();

//        if($user){
//            FailedRow::query()->create([
//                'reason' => 'Bu eposta adresi ile kayıtlı kullanıcı zaten var',
//                'value' => $row['tc'] . " ". $row['eposta'],
//                'row_number' => 0
//            ]);
//            return null;
//        }


        $nonEmptyPhone =  str_replace(' ', '',  $row['telefon']);
        $phone = substr($nonEmptyPhone, -10);

        $user = User::where('phone_number', $phone)->first();

        if($user){
            FailedRow::query()->create([
                'reason' => 'Bu telefon numarası ile kayıtlı kullanıcı zaten var',
                'value' => $row['telefon'] . " ". $row['eposta'],
                'row_number' => 0
            ]);
            return null;
        }


        if(strlen($phone) != 10){
            FailedRow::query()->create([
                'reason' => 'Telefon numarası başında 0 olmadan 10 haneli olmalıdır.',
                'value' => $row['telefon'] . " ". $row['eposta'] . " - ". $phone,
                'row_number' => 0
            ]);
            return null;
        }

        return new User([
              'name'     => $row['adsoyad'],
              'identity_number'    => $row['tc'],
              'email' => 'noemail@mail.com',
              'phone_number' => $phone,
              'role' => 'USER',
              'password' => Hash::make(12121221128997645),
              'sms_verified_at' => null,
              'can_do_reservation' =>true
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
