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
                                <div class="col-9">
                                    <h6>Kullanıcı Bilgileri</h6>
                                    <p class="text-sm">Sisteme kayıtlı kullanıcı bilgilerini içerir.</p>
                                </div>


                                <div class=" col-sm-12 col-md-12 col-lg-3">
                                    <a class="btn btn-outline-primary btn-sm mb-0" href="{{route('user.create')}}">
                                        Manuel Kullanıcı Kayıdı Ekle
                                    </a>
                                </div>

                            </div>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">

                           <div class="col-12">
                               <form action="" method="GET">

                                   <div class="row p-4">
                                       <div class="col-md-6 col-sm-12 col-lg-6 pb-3">
                                           <input type="text" value="{{Request::get('searchKey')}}" placeholder="Ara..." name="searchKey" class="form-control">
                                       </div>
                                       <div class="col-md-3 col-sm-12 col-lg-3 pb-2">
                                           <button type="submit" class="btn btn-primary" style="width: 100%">Filtrele</button>
                                       </div>

                                       <div class="col-md-3 col-sm-12 col-lg-3 ">
                                           <a href="{{route('user.index')}}" style="width: 100%" class="btn btn-secondary">Sıfırla</a>
                                       </div>


                                   </div>
                               </form>
                           </div>

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            İsim Soyisim
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            T.C. Kimlik No
                                        </th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Telefon Numarası
                                        </th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{$user->name}}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{$user->identity_number}}</p>
                                            </td>

                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$user->phone_number}}</span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{route('user.edit', $user->id)}}"
                                                   class="btn btn-sm btn-secondary text-secondary text-white font-weight-bold text-xs"
                                                   data-toggle="tooltip" data-original-title="Edit user">
                                                    Bilgeri Düzenle
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{ $users->appends($_GET)->links('pagination::bootstrap-5') }}
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


