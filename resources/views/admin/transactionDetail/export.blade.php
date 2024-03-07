<table class="table align-items-center mb-0">
    <thead>
    <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rezervasyon ID</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kullanıcı</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ödeme Tipi</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Durum</th>
        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ödenen Tutar</th>
        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Hata Sebebi</th>
        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Oluşturulma Tarihi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($details as $item)
        <tr>
            <td class="">
                <p class="text-xs font-weight-bold mb-0">{{$item->reservation_id}}</p>
            </td>
            <td class="">
                @if ($item->reservation && $item->reservation->user)
                    <p class="text-xs font-weight-bold mb-0">{{$item->reservation->user->name}}</p>
                @else
                    <!-- Handle the case when either 'reservation' or 'user' is null -->
                @endif
            </td>
            <td class="">
                <p class="text-xs font-weight-bold mb-0">
                    @if($item->payment_method == 'bank_transfer')
                        Havale

                    @else
                        Kredi Kartı
                    @endif

                </p>
            </td>
            <td class="">
                @if($item->status == true)

                    <span class="badge badge-sm bg-gradient-success">
                                                   Başarılı</span>

                @else
                    <span class="badge badge-sm bg-gradient-danger">
                                                   Hata</span>
                @endif
            </td>
            <td class="">
                <p class="text-xs font-weight-bold mb-0">{{moneyFormat($item->paid_amount)}}</p>
            </td>

            <td class="">
                <p class="text-xs font-weight-bold mb-0">{{$item->error_reason}}</p>
            </td>

            <td class="">
                <p class="text-xs font-weight-bold mb-0">{{$item->created_at}}</p>
            </td>
        </tr>

    @endforeach
    </tbody>
</table>
