<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Setting;
use App\Models\SmsVerification;
use App\Models\User;

class SmsService
{

    private $username;
    private $password;
    private $header;

    public function __construct()
    {
        $this->username = 3129110656;
        $this->password = 'w3.5QyXV';
    }


    public function sendVerificationSms($user)
    {
        $rand = rand(111111, 999999);
        $gsm = $user->phone_number;

        $msg = Setting::query()->where('key', 'login_sms')->first()->value .": ".  $rand;

      try{
          $curl = curl_init();

          curl_setopt_array($curl, array(
              CURLOPT_URL => 'http://soap.netgsm.com.tr:8080/Sms_webservis/SMS?wsdl/',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => '<?xml version="1.0"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <SOAP-ENV:Body>
            <ns3:smsGonder1NV2 xmlns:ns3="http://sms/">
                <username>'.$this->username.'</username>
                <password>'.$this->password.'</password>
                <header>MEDYA IS</header>
                <msg>'.$msg.'</msg>
                <gsm>'.$gsm.'</gsm>
                <filter>0</filter>
                <encoding>TR</encoding>
            </ns3:smsGonder1NV2>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>',
              CURLOPT_HTTPHEADER => array(
                  'Content-Type: text/xml'
              ),
          ));

          $response = curl_exec($curl);

          curl_close($curl);

          $this->saveUserSmsData($user, $rand); // save sms data to database
      }catch (\Exception $exception) {
          return false;
      }

        return true;
    }

    private function saveUserSmsData(User $user, int $rand)
    {
        SmsVerification::query()
                       ->create([
                                    'user_id' => $user->id,
                                    'code' => $rand,
                                    'phone_number' => $user->phone_number,
                                    'expires_at' => now()->addMinutes(5)
                                ]);
    }

    public function sendBankInformationSms($user, $code)
    {
        $gsm = $user->phone_number;
        $msg = Setting::query()->where('key', 'iban_sms')->first()->value;

        $reservation = Reservation::query()->where('bank_transfer_code', $code)->first();
        $price = moneyFormatF(($reservation->total_amount * 30) / 100);

        $msg .= " Rezervasyonun onaylanması için ödenecek tutar: ". $price ." açıklama kısmına aşağıdaki kodu eklemeyi unutmayınız aksi takdirde iptal edilecektir! Kod: " . $code;

        try{
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://soap.netgsm.com.tr:8080/Sms_webservis/SMS?wsdl/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '<?xml version="1.0"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <SOAP-ENV:Body>
            <ns3:smsGonder1NV2 xmlns:ns3="http://sms/">
                <username>'.$this->username.'</username>
                <password>'.$this->password.'</password>
                <header>MEDYA IS</header>
                <msg>'.$msg.'</msg>
                <gsm>'.$gsm.'</gsm>
                <filter>0</filter>
                <encoding>TR</encoding>
            </ns3:smsGonder1NV2>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/xml'
                ),
            ));

            $response = curl_exec($curl);


            curl_close($curl);

        }catch (\Exception $exception) {
            dd($exception);
            return false;
        }

        return true;
    }


    public function sendOrderApprovedSms($user)
    {
        $gsm = $user->phone_number;
        $msg = Setting::query()->where('key', 'reservation_approved_sms')->first()->value;

        try{
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://soap.netgsm.com.tr:8080/Sms_webservis/SMS?wsdl/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '<?xml version="1.0"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <SOAP-ENV:Body>
            <ns3:smsGonder1NV2 xmlns:ns3="http://sms/">
                <username>'.$this->username.'</username>
                <password>'.$this->password.'</password>
                <header>MEDYA IS</header>
                <msg>'.$msg.'</msg>
                <gsm>'.$gsm.'</gsm>
                <filter>0</filter>
                <encoding>TR</encoding>
            </ns3:smsGonder1NV2>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/xml'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

        }catch (\Exception $exception) {
            return false;
        }

        return true;
    }


    public function sendPaymentSuccessSms($user)
    {
        $gsm = $user->phone_number;
        $msg = Setting::query()->where('key', 'payment_success_sms')->first()->value;

        try{
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://soap.netgsm.com.tr:8080/Sms_webservis/SMS?wsdl/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '<?xml version="1.0"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <SOAP-ENV:Body>
            <ns3:smsGonder1NV2 xmlns:ns3="http://sms/">
                <username>'.$this->username.'</username>
                <password>'.$this->password.'</password>
                <header>MEDYA IS</header>
                <msg>'.$msg.'</msg>
                <gsm>'.$gsm.'</gsm>
                <filter>0</filter>
                <encoding>TR</encoding>
            </ns3:smsGonder1NV2>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/xml'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

        }catch (\Exception $exception) {
            return false;
        }

        return true;
    }


    public function sendUserApprovedSms($user)
    {
        $gsm = $user->phone_number;
        $msg = Setting::query()->where('key', 'user_approved_sms')->first()->value;

        try{
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://soap.netgsm.com.tr:8080/Sms_webservis/SMS?wsdl/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '<?xml version="1.0"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <SOAP-ENV:Body>
            <ns3:smsGonder1NV2 xmlns:ns3="http://sms/">
                <username>'.$this->username.'</username>
                <password>'.$this->password.'</password>
                <header>MEDYA IS</header>
                <msg>'.$msg.'</msg>
                <gsm>'.$gsm.'</gsm>
                <filter>0</filter>
                <encoding>TR</encoding>
            </ns3:smsGonder1NV2>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/xml'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

        }catch (\Exception $exception) {
            return false;
        }

        return true;
    }




}
