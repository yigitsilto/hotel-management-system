<?php

namespace App\Services;

use App\Models\HesapEkstreRequest;

class BankTransferCheckService
{

    public function check()
    {

        //$url = 'http://webservicestest.halkbank.com.tr/HesapEkstreOrtakWS/HesapEkstreOrtak.svc';
        $url = 'https://webservice.halkbank.com.tr/HesapEkstreOrtakWS/HesapEkstreOrtak.svc?wsdl';
        // BANKANIN SİZE VERDİĞİ KULLANICI ADI ŞİFRE
        $username = "28355363EPDUSR";
        $password = 'Li3N*wMTvi ';

// İKİ TARİH ARASI DEĞERLER
        $start_date = '2024-01-14';
        $end_date = '2024-02-29';

        $wsse_header = new WsseAuthHeader($username, $password);
        $client = new \SoapClient($url);
        $client->__setSoapHeaders(array($wsse_header));

// Daha sonra sınıfımdaki objelere değerleri atadım.
        $request = new HesapEkstreRequest();
        $request->BaslangicTarihi = $start_date;
        $request->BitisTarihi = $end_date;
        $requestParams = array('request' => $request);


        $reservations = \App\Models\Reservation::query()
            ->withoutGlobalScope('payment_status')
            ->where('payment_method', 'bank_transfer')
            ->where('payment_status', false)
            ->get();

        try {
            // dd($client);
// Burda en çok kullanılan metodu örnekledim, birde bağlı müşteri metodu var onuda göstereceğim
            $response = $client->EkstreSorgulama($requestParams);

            // EkstreSorgulamaResult içindeki Hesaplar dizisi üzerinde döngü
            foreach ($response->EkstreSorgulamaResult->Hesaplar->Hesap->Hareketler->Hareket as $hareket) {
                $aciklama = $hareket->EkstreAciklama;
                $tarih = $hareket->Tarih;
                $saat = $hareket->Saat;
                $bakiye = $hareket->Bakiye;
                $tutar = $hareket->HareketTutari;

                foreach ($reservations as $reservation) {
                    $rezCode = $reservation->bank_transfer_code;
                    if (strpos($aciklama, $rezCode) !== false) {
                        // $aciklama içinde $rezCode bulundu
                        // Burada istediğiniz işlemi gerçekleştirebilirsiniz
                        // Örneğin, kullanıcı açıklamasına $rezCode'u ekleyebilirsiniz
                        dd("bulundu", $rezCode, $aciklama);
                    }

                }


            }


        } catch (\Exception $e) {
            dd($e);
        }
        dd($response, $reservations->toArray());
    }

}
