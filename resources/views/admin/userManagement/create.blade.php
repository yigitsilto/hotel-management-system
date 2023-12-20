@extends('layouts.admin')
@section('title', 'Anasayfa')
@section('content')


    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-1">Yeni Kullanıcı Ekle</h6>
                            <p class="text-sm">Manuel kullanıcı kaydı ekler.</p>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-12">
                                    <form action="{{ route('user-management.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">İsim Soyisim</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                                                    @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="identity_number" class="form-label">Tc Kimlik Numarası</label>
                                                    <input type="text" required class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number">
                                                    @error('identity_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="phone_number" class="form-label">Telefon Numarası</label>
                                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" required name="phone_number">
                                                    @error('phone_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="total_rooms" class="form-label">Cinsiyet</label>
                                                    <select class="form-control" name="gender" id="">
                                                        <option value="Erkek">Erkek</option>
                                                        <option value="Kadın">Kadın</option>
                                                        <option value="Belirtmedi">Belirtmek İstemiyorum</option>
                                                    </select>
                                                    @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="phone_number" class="form-label">E-Posta</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" required name="email">
                                                    @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Şifre</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" required name="password">
                                                    @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password_Confirmation" class="form-label">Şifre Tekrarı</label>
                                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password" required name="password_confirmation">
                                                    @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Kaydet</button>
                                    </form>

                                </div>

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
        $(document).ready(function () {
            $('#phone_number').inputmask('999-999-9999', { placeholder: '' });
        });
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


