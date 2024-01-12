<?php

namespace App\Services;

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
                <msg>Sms Kodunuz ve aynı zamanda yeni şifreniz : '.$rand.'</msg>
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

    public function sendBankInformationSms($user)
    {
        $gsm = $user->phone_number;

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
                <msg>Banka Bilgilerimiz : TR1231231123123  </msg>
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


    public function sendOrderApprovedSms($user)
    {
        $gsm = $user->phone_number;

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
                <msg>Rezervasyonunuz onaylanmıştır.</msg>
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
                <msg>Ödemeniz için teşekkürler.</msg>
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