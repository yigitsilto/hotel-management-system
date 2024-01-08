@extends('layouts.user')
@section('title', 'Anasayfa')
@section('content')
    <style>
        #hotel-card:hover{
            transform: translateY(-5px); /* veya istediğiniz miktarda yukarı çıkarmak için bir değer belirleyin */

        }
        .inactive{
            opacity: 0.5;
        }
    </style>
    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0 p-3">
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12">
                                    <h6 class="mb-1">{{ $hotel->name }} İsimli Otel için Rezervasyon Ekranı</h6>
                                    <p class="text-sm"></p>
                                </div>
                            </div>

                        </div>
                        <div class="card-body p-3">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    <span style="color: white;"> {{ session('success') }}</span>
                                </div>
                            @endif
                            <div class="row">
                                @foreach($rooms as $item)

                                    <div class="col-xl-3 col-md-6 mb-xl-0 mb-4 mt-4" id="hotel-card" >
                                        <div class="card card-blog card-plain">
                                            <div class="position-relative">
                                                <a href="{{route('user-reservation.showRoom', $item->id)}}"  class="d-block shadow-xl border-radius-xl cursor-pointer">
                                                    <img src="{{ asset($item->base_image) }}" alt="img-blur-shadow" style="width: 100%; height: 250px; object-fit: cover" class="img-fluid shadow border-radius-xl">
                                                </a>
                                            </div>
                                            <div class="card-body px-1 pb-0">
                                                <p class="text-gradient text-dark mb-2 text-sm">Günlük Ücret: {{moneyFormat($item->price)}}</p>
                                                <p class="text-gradient text-dark mb-2 text-sm">Oda Tipi: {{$item->room_type}}</p>
                                                <p class="text-gradient text-dark mb-2 text-sm">Kişi Sayısı: {{$item->capacity}}</p>
                                                <a href="{{route('user-reservation.showRoom', $item->id)}}">
                                                    <h5>
                                                        {{$item->title}}
                                                    </h5>
                                                </a>
                                                <div class="mb-4 text-sm flex" >
                                                    {!! Str::limit($item->description, 70) !!}
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <a href="{{route('user-reservation.showRoom', $item->id)}}" type="button" class="btn btn-outline-primary btn-sm mb-0">Detay</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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


