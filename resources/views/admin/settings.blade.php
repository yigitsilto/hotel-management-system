@extends('layouts.admin')
@section('title', 'Anasayfa')
@section('content')


    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-1">Ayarlar</h6>
                            <p class="text-sm">Sms Ayarları.</p>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-12">
                                        <div class="row">
                                            <form method="POST" action="{{ route('settings.update') }}">
                                                @csrf
                                                @foreach ($settings as $setting)
                                                    @if($setting->key != 'iban_special_text')

                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label for="{{ $setting->key }}" class="form-label">{{ __('settings.' . $setting->key) }}</label>
                                                                <input type="text" class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" required>
                                                            </div>
                                                        </div>

                                                    @else

                                                        <textarea id="editor" name="{{ $setting->key }}">{!! $setting->value  !!}</textarea>

                                                    @endif
                                                @endforeach


                                                <div class="col-md-12 pt-3">
                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary">Kaydet</button>
                                                    </div>
                                                </div>
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
            <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

            <script>

        $(document).ready(function () {
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );

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


