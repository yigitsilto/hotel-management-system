@extends('layouts.admin')
@section('title', 'Anasayfa')
@section('content')
    <div class="container-fluid">
        <div class="page-header min-height-100 border-radius-xl mt-4" >
        </div>
        <div class="card card-body blur shadow-blur  mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{asset($room->base_image)}}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{$room->title}}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            Toplam Ücret: {{moneyFormat($reservation->total_amount)}} - Ödenen Ücret: {{moneyFormat($reservation->paid_amount)}} - Ödeme Tipi: {{$reservation->payment_method == 'bank_transfer' ? 'Havale' : 'Kredi Kartı'}}
                        </p>


                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="container-fluid">
        <div class="page-header min-height-100 border-radius-xl mt-1" >
        </div>
        <div class="card card-body blur shadow-blur  mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-12">

                    <form action="{{route('reservation.update', $reservation->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <label for="reservation_status">Rezervasyon Durumu</label>
                                <select name="reservation_status" class="form-control" id="reservation_status">
                                    @foreach(\App\Enums\ReservationStatusEnum::getValues() as $key => $item)
                                        <option value="{{$key}}" @if($key == $reservation->reservation_status) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <label for="reservation_status">Toplam Ödeme Tutarı</label>
                                <input type="text" name="total_amount" class="form-control" id="total_amount" value="{{$reservation->total_amount}}">
                                @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 ">
                                <label for="reservation_status">Alınan Toplam Ödeme Tutarı</label>
                                <input type="text" name="paid_amount" class="form-control" id="paid_amount" value="{{$reservation->paid_amount}}">
                                @error('paid_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 ">
                                <label for=""></label>
                                <button  class="form-control btn btn-sm btn-primary mt-1" href="{{route('user-reservation.myReservations.requestCancelReservation', $room->id)}}" role="tab" aria-selected="true">
                                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(603.000000, 0.000000)">
                                                        <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                        </path>
                                                        <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                        <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="ms-1">Değişiklikleri Kaydet</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0">Otel Bilgisi</h6>
                            </div>

                        </div>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Otel Adı: </strong>{{$hotel->name}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Otel Lokasyonu: </strong>{{$hotel->location}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">İletişim E-Posta: </strong>{{$hotel->contact_email}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">İletişim Telefon Numarası: </strong>{{$hotel->contact_phone}}</li>
                            <hr>
                            <img src="{{asset($hotel->image)}}" style="width: 100%; height: 250px; border-radius: 10px;object-fit: cover" alt="">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0">Oda Bilgisi</h6>
                            </div>

                        </div>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Oda Adı: </strong>{{$room->title}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Oda Tipi: </strong>{{$room->room_type}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Oda Kişi Kapasitesi: </strong>{{$room->capacity}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Günlük Ücret: </strong>{{moneyFormat($room->price)}}</li>
                            <hr>
                            <img src="{{asset($room->base_image)}}" style="width: 100%; height: 250px; border-radius: 10px; object-fit: cover" alt="">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0">Rezervasyon Bilgisi</h6>
                            </div>

                        </div>
                    </div>
                    <div class="card-body p-3 ">
                        <ul class="list-group">
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Kişi Sayısı: </strong>{{$reservation->number_of_guests}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Giriş Tarihi: </strong>{{\Carbon\Carbon::make($reservation->check_in_date)->format('d-m-Y')}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Çıkış Tarihi: </strong>{{\Carbon\Carbon::make($reservation->check_out_date)->format('d-m-Y')}}</li>
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Ödeme Bilgisi: </strong>{{$reservation->payment_method == 'credit_card' ? 'Kredi Kartı' : 'Havale - Açıklama Kodu :  '. $reservation->bank_transfer_code}}</li>
                            @if(!is_null($reservation->special_requests))
                                <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Not: </strong>{{$reservation->special_requests}}</li>
                            @endif

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>İsim</th>
                                        <th>T.C.</th>
                                        <th>Yaş</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reservation->guests as $guest)
                                        <tr>
                                            <td style="font-size: 14px">{{$guest->name}}</td>
                                            <td style="font-size: 14px">{{$guest->tc}}</td>
                                            <td style="font-size: 14px">{{$guest->age}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <hr>
                        </ul>
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


