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
                                <div class="col-md-10 col-sm-12 col-lg-10">
                                    <h6>{{$hotel->name}} - Kayıtlı Oda Bilgileri</h6>
                                    <p class="text-sm">Sisteme kayıt ettiğiniz otelin oda bilgilerini içerir.</p>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                  @if(auth()->user()->role == 'ADMIN')
                                        <a class="btn btn-outline-primary btn-sm mb-0" href="{{route('room-management.create',$hotel->id)}}">
                                            Yeni Oda Kayıdı Ekle
                                        </a>
                                  @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Başlık</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kapasite</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Günlük Fiyat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Müsaitlik Durumu</th>
                                        <th class="text-secondary opacity-7"></th>
                                        <th class="text-secondary opacity-7"></th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                   @foreach($rooms as $room)
                                       <tr>
                                           <td>
                                               <p class="text-xs font-weight-bold mb-0">{{$room->title}}</p>
                                           </td>
                                           <td>
                                               <p class="text-xs font-weight-bold mb-0">{{$room->capacity}}</p>
                                           </td>

                                           <td class="align-middle text-center">
                                               <span class="text-secondary text-xs font-weight-bold">{{$room->price}}</span>
                                           </td>
                                           <td class="align-middle text-center text-sm">
                                               <span class="badge badge-sm bg-gradient-{{$room->is_available == 1 ? 'success': 'danger'}}">{{$room->is_available == 1 ? 'Müsait': 'Müsait Değil'}}</span>
                                           </td>
                                          @if(auth()->user()->role == 'ADMIN')
                                               <td class="align-middle">
                                                   <a href="{{route('room-management.edit', $room->id)}}" class="btn btn-sm btn-secondary text-secondary text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                       Bilgeri Düzenle
                                                   </a>
                                               </td>
                                               <td class="align-middle">
                                                   <a href="{{route('room-management.upload.images.create', $room->id)}}" class="btn btn-sm btn-secondary text-secondary text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                       Resimleri Düzenle
                                                   </a>
                                               </td>
                                          @endif
                                           <td class="align-middle">
                                               <a href="{{route('reservation-management.manuel.create', $room->id)}}" class="btn btn-sm btn-primary text-secondary text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                   Manuel Rezervasyon Yap
                                               </a>
                                           </td>
                                       </tr>

                                   @endforeach
                                    </tbody>
                                </table>
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


