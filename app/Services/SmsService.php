<?php

namespace App\Services;

use App\Models\SmsVerification;
use App\Models\User;

class SmsService
{

    public function sendVerificationSms($user)
    {
        $rand = rand(111111, 999999);
        $this->saveUserSmsData($user, $rand); // save sms data to database
        $gsm = $user->phone_number;
/*        $xml = '<?xml version="1.0" encoding="UTF-8"?>   <mainbody>   <header>   <company dil="TR">Netgsm</company>   <usercode>3129110656</usercode>   <password>N9KYZ1PZ</password>   <type>n:n</type>   <msgheader>MEDYA IS</msgheader>   </header>   <body>   <mp><msg><![CDATA[' . $rand . ' doğrulama kodunuz.]]></msg><no>' . $gsm . '</no></mp>   </body>   </mainbody>';*/
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'https://api.netgsm.com.tr/sms/send/xml');
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
//        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
//        $result = curl_exec($ch);
//        return $result;
        return true;
    }

    public function saveUserSmsData(User $user, int $rand)
    {
        SmsVerification::query()
                       ->create([
                                    'user_id' => $user->id,
                                    'code' => $rand,
                                    'phone_number' => $user->phone_number,
                                    'expires_at' => now()->addMinutes(5)
                                ]);
    }

}