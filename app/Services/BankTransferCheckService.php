<?php

namespace App\Services;

use App\Models\HesapEkstreRequest;

class BankTransferCheckService
{



    public function check() {

        //$url = 'http://webservicestest.halkbank.com.tr/HesapEkstreOrtakWS/HesapEkstreOrtak.svc';
        $url = 'https://webservice.halkbank.com.tr/HesapEkstreOrtakWS/HesapEkstreOrtak.svc?wsdl';
        // BANKANIN SİZE VERDİĞİ KULLANICI ADI ŞİFRE
        $username = "28355363EPDUSR";
        $password = 'Li3N*wMTvi ';

// İKİ TARİH ARASI DEĞERLER
        $start_date = '2024-01-01';
        $end_date = '2024-02-26';

        $wsse_header = new WsseAuthHeader($username, $password);
        $client = new \SoapClient($url);
        dd($client);
        $client->__setSoapHeaders(array($wsse_header));

// Daha sonra sınıfımdaki objelere değerleri atadım.
        $request = new HesapEkstreRequest();
        $request->BaslangicTarihi=$start_date;
        $request->BitisTarihi=$end_date;
        $requestParams = array('request' => $request);

        try
        {
// Burda en çok kullanılan metodu örnekledim, birde bağlı müşteri metodu var onuda göstereceğim
            $response=$client->EkstreSorgulama($requestParams);
        }
        catch(\Exception $e)
        {
            var_dump($client->__gsetLastRequest());
            var_dump($e);
        }
        var_dump($response);
    }

}
