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
                                    <h6>Ödeme Bilgileri</h6>
                                    <p class="text-sm">Sistemde tüm başarılı ve hatalı ödemeleri listeler.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">

                            <div class="col-12">
                                <form action="" method="GET">

                                    <div class="row p-4">
                                        <div class="col-3">
                                            <input type="text" value="{{Request::get('searchKey')}}" placeholder="Ara..." name="searchKey" class="form-control">
                                        </div>
                                        <div class="col-3">
                                            <select name="statusKey" class="form-control" id="">
                                                <option value="all" {{ Request::get('statusKey') == 'all' ? 'selected' : '' }}>Hepsi</option>
                                                <option value="true" {{ Request::get('statusKey') == 'true' ? 'selected' : '' }}>Başarılı</option>
                                                <option value="false" {{ Request::get('statusKey') == 'false' ? 'selected' : '' }}>Hatalı</option>
                                            </select>

                                        </div>
                                        <div class="col-3">
                                            <button type="submit" class="btn btn-primary" style="width: 100%">Filtrele</button>
                                        </div>

                                        <div class="col-3">
                                            <a href="{{route('transaction-management.index')}}" style="width: 100%" class="btn btn-secondary">Sıfırla</a>
                                        </div>


                                    </div>
                                </form>
                            </div>


                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reservasyon ID</th>
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
                                                <p class="text-xs font-weight-bold mb-0">{{$item->reservation->user->name}}</p>
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

                            </div>

                        </div>
                        {{ $details->appends($_GET)->links('pagination::bootstrap-5') }}

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


