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
                                    <h6 class="mb-1">{{ $room->title }}</h6>
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


