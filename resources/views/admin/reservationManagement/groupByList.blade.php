@extends('layouts.admin')
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
                                    <h6>Rezervasyon Doluluk Oranları</h6>
                                    <p class="text-sm">Oluşturulmuş rezervasyon taleplerini belli bir tarihe göre içerir.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">

                            <div class="col-12">
                                <form action="" method="GET">

                                    <div class="row p-4">
                                        <div class="col-md-3 col-sm-12">
                                            <input type="date" value="{{Request::get('checkIn')}}"  name="checkIn" class="form-control">
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <input type="date" value="{{Request::get('checkOut')}}"  name="checkOut" class="form-control">
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <button type="submit" class="btn btn-primary" style="width: 100%">Filtrele</button>
                                        </div>

                                        <div class="col-md-3 col-sm-12">
                                            <a href="{{route('groupByList')}}" style="width: 100%" class="btn btn-secondary">Sıfırla</a>
                                        </div>


                                    </div>
                                </form>
                            </div>

{{--                            <div class="col-12">--}}
{{--                                        <div class="col-3 p-1">--}}
{{--                                            <a href="{{route('excel-export-reservation')}}" style="width: 100%" class="btn btn-success">Excele Aktar</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                            </div>--}}
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Giriş Tarihi</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Çıkış Tarihi</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mevcut Rezervasyon Sayısı</th>
{{--                                        <th class="text-secondary opacity-7"></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                   @if(!empty($reservations))
                                       @foreach($reservations as $item)
                                           <tr>
                                               <td class="align-middle ">
                                                   <p class="text-xs font-weight-bold mb-0">{{\Carbon\Carbon::make($item->check_in_date)->format('d-m-Y')}}</p>
                                               </td>

                                               <td class="align-middle ">
                                                   <p class="text-xs font-weight-bold mb-0">{{\Carbon\Carbon::make($item->check_out_date)->format('d-m-Y')}}</p>
                                               </td>

                                               <td class="align-middle ">
                                                   <p class="text-md font-weight-bold mb-0">{{$item->reservation_count}}</p>
                                               </td>


{{--                                               <td class="align-middle">--}}
{{--                                                   <a href="{{route('reservation.show', $item->id)}}" class="btn btn-sm btn-secondary text-secondary text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">--}}
{{--                                                       Detay--}}
{{--                                                   </a>--}}
{{--                                               </td>--}}
                                           </tr>

                                       @endforeach
                                   @endif
                                    </tbody>
                                </table>

                            </div>

                        </div>
{{--                        {{ $reservations->appends($_GET)->links('pagination::bootstrap-5') }}--}}

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


