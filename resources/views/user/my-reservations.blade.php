@extends('layouts.user')
@section('title', 'Anasayfa')
@section('content')
    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="row mt-3">
                                <div class="col-10">
                                    <h6>Reservasyon Taleplerim</h6>
                                    <p class="text-sm">Oluşturmuş olduğunuz rezervasyon taleplerini içerir.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Otel Adı</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kişi Sayısı</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ücret</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ödenen Ücret</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ödenmesi Gereken Ücret</th>
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
                                               <p class="text-xs font-weight-bold mb-0">{{$item->room->hotel->name}}</p>
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
                                               <p class="text-xs font-weight-bold mb-0">{{moneyFormat(($item->total_amount * 30) / 100)}}</p>
                                           </td>

                                           <td class="align-middle text-center">
                                               <span class="text-secondary text-xs font-weight-bold">{{\Carbon\Carbon::make($item->check_in_date)->format('d-m-Y')}}</span>
                                           </td>

                                           <td class="align-middle text-center">
                                               <span class="text-secondary text-xs font-weight-bold">{{\Carbon\Carbon::make($item->check_out_date)->format('d-m-Y')}}</span>
                                           </td>
                                           <td class="align-middle text-center text-sm">
                                               <span class="badge badge-sm bg-gradient-info">
                                                   {{\App\Enums\ReservationStatusEnum::getValueByKey($item->reservation_status)}}</span>
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
                                               <a href="{{route('user-reservation.myReservations.detail', $item->id)}}" class="btn btn-sm btn-secondary text-secondary text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                   Detay
                                               </a>
                                           </td>
                                       </tr>

                                   @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                        {{ $reservations->links('pagination::bootstrap-5') }}

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script')
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
@endsection
<!-- Github buttons -->


