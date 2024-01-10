@extends('layouts.user')
@section('title', 'Anasayfa')
@section('content')
    <style>
        #room-card:hover {
            border: 1px solid whitesmoke;
            cursor: pointer;

        }

        .inactive {
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
                                    <div class="card  col-12 p-2" id="room-card">
                                        <div class="row g-0">
                                            <div class="col-md-3">
                                                <a href="{{route('user-reservation.showRoom', $item->id)}}">
                                                    <img src="{{ asset($item->base_image) }}"  style="border-radius: 10px; height: 100%; width: 100%; object-fit: cover"
                                                         class="img-fluid rounded-start" alt="hotel"/>
                                                </a>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{$item->title}}</h5>
                                                    <div class="card-text" style="display: -webkit-box;
    -webkit-line-clamp: 2; /* Kaç satır gösterileceğini belirtir */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;">
                                                        {!! $item->description !!}
                                                    </div>
                                                    <p class="card-text mt-3">
                                                        <span class="font-weight-bold">Günlük Ücret:</span> {{moneyFormat($item->price)}}

                                                        -
                                                        <span class="font-weight-bold">Oda Tipi:</span> {{$item->room_type}}
                                                        -
                                                        <span class="font-weight-bold">Kişi Sayısı:</span> {{$item->capacity}}

                                                    </p>

                                                    <a href="{{route('user-reservation.showRoom', $item->id)}}"
                                                       class="btn btn-sm btn-primary">Detay</a>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            {{--                            <div class="row">--}}
                            {{--                                @foreach($rooms as $item)--}}

                            {{--                                    <div class="col-xl-3 col-md-6 mb-xl-0 mb-4 mt-4" id="hotel-card" >--}}
                            {{--                                        <div class="card card-blog card-plain">--}}
                            {{--                                            <div class="position-relative">--}}
                            {{--                                                <a href="{{route('user-reservation.showRoom', $item->id)}}"  class="d-block shadow-xl border-radius-xl cursor-pointer">--}}
                            {{--                                                    <img src="{{ asset($item->base_image) }}" alt="img-blur-shadow" style="width: 100%; height: 250px; object-fit: cover" class="img-fluid shadow border-radius-xl">--}}
                            {{--                                                </a>--}}
                            {{--                                            </div>--}}
                            {{--                                            <div class="card-body px-1 pb-0">--}}
                            {{--                                                <p class="text-gradient text-dark mb-2 text-sm">Günlük Ücret: {{moneyFormat($item->price)}}</p>--}}
                            {{--                                                <p class="text-gradient text-dark mb-2 text-sm">Oda Tipi: {{$item->room_type}}</p>--}}
                            {{--                                                <p class="text-gradient text-dark mb-2 text-sm">Kişi Sayısı: {{$item->capacity}}</p>--}}
                            {{--                                                <a href="{{route('user-reservation.showRoom', $item->id)}}">--}}
                            {{--                                                    <h5>--}}
                            {{--                                                        {{$item->title}}--}}
                            {{--                                                    </h5>--}}
                            {{--                                                </a>--}}

                            {{--                                                <div class="d-flex align-items-center justify-content-between">--}}
                            {{--                                                    <a href="{{route('user-reservation.showRoom', $item->id)}}" type="button" class="btn btn-outline-primary btn-sm mb-0">Detay</a>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                @endforeach--}}
                            {{--                            </div>--}}
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


