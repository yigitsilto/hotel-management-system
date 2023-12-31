<?php

namespace App\Services;

class TCService
{

    private string $ad;
    private string $soyad;
    private $dogumYili;
    private string $tcKimlikNo;

    /**
     * Sorgulama
     * @return bool
     * @throws \Exception
     */
    public function sorgula($ad, $soyad, $dogumYili, $tcKimlikNo)
    {
        $this->ad = $ad;
        $this->soyad = $soyad;
        $this->dogumYili = $dogumYili;
        $this->tcKimlikNo = $tcKimlikNo;

        $toSend =
            '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
      <TCKimlikNo>'.$tcKimlikNo.'</TCKimlikNo>
      <Ad>'.$ad.'</Ad>
      <Soyad>'.$soyad.'</Soyad>
      <DogumYili>'.$dogumYili.'</DogumYili>
    </TCKimlikNoDogrula>
  </soap:Body>
</soap:Envelope>';


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
            'Content-Length: ' . strlen($toSend)
        ));
        $response = curl_exec($ch);


        // Hata kontrolü
        if (curl_errno($ch)) {
            return false; // cURL hatası varsa false döndür
        }

        curl_close($ch);

        // XML'den sonucu çıkar
        $result = strip_tags($response);

        return $result == 'true';
    }

    /**
     * Algoritmik olarak dogrulama yapar
     * @return bool
     */
    public function dogrula()
    {
        // 11 karakterden oluşmalıdır
        if (strlen($this->tcKimlikNo) != 11) {
            return false;
        }
        // Rakamlardan oluşmalıdır
        if (!preg_match('/(?<!\S)\d++(?!\S)/', $this->tcKimlikNo)) {
            return false;
        }
        // Algoritmik hesaplamalar
        $digit = preg_split('//', $this->tcKimlikNo, -1, PREG_SPLIT_NO_EMPTY);
        if ($digit[0] == 0) {
            return false;
        }
        $odd = $digit[0] + $digit[2] + $digit[4] + $digit[6] + $digit[8];
        $even = $digit[1] + $digit[3] + $digit[5] + $digit[7];
        $digit10 = ($odd * 7 - $even) % 10;
        $total = ($odd + $even + $digit[9]) % 10;
        if ($digit10 != $digit[9] or $total != $digit[10]) {
            return false;
        }

        return true;
    }
}