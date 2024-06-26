<table class="table align-items-center mb-0">
    <thead>
    <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ID</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Otel Adı</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">İsim Soyisim</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kişi Sayısı</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ücret</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ödenen Ücret</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Giriş Tarihi</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Çıkış Tarihi</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durumu</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bilgi</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Müşteri Bilgileri</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">İşlem Tarihi</th>
        <th class="text-secondary opacity-7"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservations as $item)
        <tr>
            <td class="align-middle text-center">
                <p class="text-xs font-weight-bold mb-0">{{$item->id}}</p>
            </td>

            <td class="align-middle text-center">
                <p class="text-xs font-weight-bold mb-0">{{$item->room->hotel->name}}</p>
            </td>
            <td class="align-middle text-center">
                <p class="text-xs font-weight-bold mb-0">
                    @if($item->user->role != 'USER')
                        Manuel Rez. - {{$item->guests[0]->name}}
                    @else
                        {{$item->user->name}}
                    @endif
            </td>
            <td class="align-middle text-center">
                <p class="text-xs font-weight-bold mb-0">{{$item->number_of_guests}}</p>
            </td>
            <td class="align-middle text-center">
                <p class="text-xs font-weight-bold mb-0">{{moneyFormat($item->total_amount)}}</p>
            </td>

            <td class="align-middle text-center">
                <p class="text-xs font-weight-bold mb-0">{{moneyFormat($item->paid_amount)}}</p>
            </td>

            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{\Carbon\Carbon::make($item->check_in_date)->format('d-m-Y')}}</span>
            </td>

            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{\Carbon\Carbon::make($item->check_out_date)->format('d-m-Y')}}</span>
            </td>
            <td class="align-middle text-center text-sm">
                <span class="badge badge-sm bg-gradient-info">{{\App\Enums\ReservationStatusEnum::getValueByKey($item->reservation_status)}}</span>
            </td>
            <td class="align-middle text-center text-sm">
                @if($item->payment_method == 'bank_transfer')
                    <span class="badge badge-sm bg-gradient-info">
                                                  Iban Açıklama Kodu: {{$item->bank_transfer_code}}</span>

                @else

                    <span class="badge badge-sm bg-gradient-info">
                                                  Kredi Kartı Ödemesi</span>
                @endif
            </td>
            <td class="align-middle text-center text-sm" style="font-size: 10px">
                <ul>

                    @foreach($item->guests as $guest)
                        <li>İsim: {{$guest->name}}</li>
                        <li>Yaş: {{$guest->age}}</li>
                        <li>TC: {{$guest->tc}}</li>
                        <li>---------------------------</li>
                    @endforeach

                </ul>

            </td>

            <td class="align-middle text-center text-sm">
                {{\Carbon\Carbon::make($item->created_at)->format('d-m-Y H:i:s')}}
            </td>
        </tr>

    @endforeach
    </tbody>
</table>
