<?php

namespace App\Services;

use App\Enums\ReservationStatusEnum;
use App\Jobs\SendOrderApprovedSmsJob;
use App\Models\HesapEkstreRequest;
use Carbon\Carbon;

class BankTransferCheckService
{

    public function check()
    {

        //$url = 'http://webservicestest.halkbank.com.tr/HesapEkstreOrtakWS/HesapEkstreOrtak.svc';
        $url = 'https://webservice.halkbank.com.tr/HesapEkstreOrtakWS/HesapEkstreOrtak.svc?wsdl';
        // BANKANIN SİZE VERDİĞİ KULLANICI ADI ŞİFRE
        $username = "28355363EPDUSR";
        $password = 'Li3N*wMTvi ';

        $start_date = Carbon::yesterday()->format('Y-m-d');
        $end_date = Carbon::today()->addDay()->format('Y-m-d');


// İKİ TARİH ARASI DEĞERLER
//        $start_date = '2024-01-14';
//        $end_date = '2024-02-29';

        $wsse_header = new WsseAuthHeader($username, $password);
        $client = new \SoapClient($url);
        $client->__setSoapHeaders(array($wsse_header));

// Daha sonra sınıfımdaki objelere değerleri atadım.
        $request = new HesapEkstreRequest();
        $request->BaslangicTarihi = $start_date;
        $request->BitisTarihi = $end_date;
        $requestParams = array('request' => $request);


        $reservations = \App\Models\Reservation::query()
            ->where('reservation_status', ReservationStatusEnum::Pending->name)
            ->where('payment_method', 'bank_transfer')
            ->get();

        try {
            // dd($client);
// Burda en çok kullanılan metodu örnekledim, birde bağlı müşteri metodu var onuda göstereceğim
            $response = $client->EkstreSorgulama($requestParams);


            // EkstreSorgulamaResult içindeki Hesaplar dizisi üzerinde döngü
            foreach ($response->EkstreSorgulamaResult->Hesaplar->Hesap->Hareketler->Hareket as $hareket) {

                dd($response, $hareket);

                $aciklama = $hareket->EkstreAciklama;
                $tarih = $hareket->Tarih;
                $saat = $hareket->Saat;
                $bakiye = $hareket->Bakiye;
                $tutar = $hareket->HareketTutari;
                $hourCarbon = Carbon::createFromFormat('H:i:s', $saat);

                foreach ($reservations as $reservation) {

                    $created_at = $reservation->created_at;


                    if ($reservation->bank_transfer_code == null) {
                        continue;
                    }


                    $rezCode = $reservation->bank_transfer_code;

                    if (strpos($aciklama, $rezCode) !== false) {


                        $mustPaidAmount = ($reservation->total_amount * 30) / 100;
                        $receivedAmountFormatted = str_replace(',', '.', $tutar); // Eğer virgül varsa noktaya dönüştür


                        if ((float)$receivedAmountFormatted >= (float)$mustPaidAmount) {
                            $reservation->payment_status = true;
                            $reservation->reservation_status = ReservationStatusEnum::Success->name;
                            $reservation->paid_amount = $receivedAmountFormatted;
                            $reservation->save();

                            $smcService = new SmsService();

                            SendOrderApprovedSmsJob::dispatch($smcService, $reservation->user);

                        }
                        // $aciklama içinde $rezCode bulundu
                        // Burada istediğiniz işlemi gerçekleştirebilirsiniz
                        // Örneğin, kullanıcı açıklamasına $rezCode'u ekleyebilirsiniz
                       // dd("sadece bulundu", $rezCode, $aciklama);
                    }

                }

            }


            $resChecks = \App\Models\Reservation::query()
                ->where('reservation_status', ReservationStatusEnum::Pending->name)
                ->where('payment_method', 'bank_transfer')
                ->get();

            $current_time = Carbon::now();

            foreach ($resChecks as $rez) {
                $created_time = Carbon::parse($rez->created_at); // Rezervasyonun oluşturulma zamanını al
                $elapsed_time = $current_time->diffInMinutes($created_time); // Geçen süreyi dakika cinsinden hesapla

                if ($elapsed_time > 10 && $rez->status === 'pending') {
                    $rez->reservation_status = ReservationStatusEnum::Rejected->name;
                    $rez->save();
                }
            }




        } catch (\Exception $e) {
            dd($e);
        }
        //dd($response, $reservations->toArray());
        return true;
    }

}
