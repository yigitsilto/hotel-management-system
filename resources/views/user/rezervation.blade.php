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
                                                <a href="{{route('hotel-management.show', $item->id)}}"  class="d-block shadow-xl border-radius-xl cursor-pointer">
                                                    <img src="{{ asset($item->base_image) }}" alt="img-blur-shadow" style="width: 100%; height: 250px; object-fit: cover" class="img-fluid shadow border-radius-xl">
                                                </a>
                                            </div>
                                            <div class="card-body px-1 pb-0">
                                                <p class="text-gradient text-dark mb-2 text-sm">Günlük Ücret: {{moneyFormat($item->price)}}</p>
                                                <p class="text-gradient text-dark mb-2 text-sm">Oda Tipi: {{$item->room_type}}</p>
                                                <p class="text-gradient text-dark mb-2 text-sm">Kişi Sayısı: {{$item->capacity}}</p>
                                                <a href="{{route('user-reservation', $item->id)}}">
                                                    <h5>
                                                        {{$item->title}}
                                                    </h5>
                                                </a>
                                                <div class="mb-4 text-sm" style="height: 80px">
                                                    {!! Str::limit($item->description, 200) !!}
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <a href="{{route('user-reservation.show', $item->id)}}" type="button" class="btn btn-outline-primary btn-sm mb-0">Detay</a>
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
            <footer class="footer pt-3  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © <script>
                                    document.write(new Date().getFullYear())
                                </script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                                for a better web.
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
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


