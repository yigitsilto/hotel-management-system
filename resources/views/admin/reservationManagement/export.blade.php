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
                <p class="text-xs font-weight-bold mb-0">{{$item->user->name}}</p>
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
            <td class="align-middle">
                <a href="{{route('reservation.show', $item->id)}}" class="btn btn-sm btn-secondary text-secondary text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                    Detay
                </a>
            </td>
        </tr>

    @endforeach
    </tbody>
</table>
