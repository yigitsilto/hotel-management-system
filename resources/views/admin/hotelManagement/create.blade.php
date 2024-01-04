@extends('layouts.admin')
@section('title', 'Anasayfa')
@section('content')
    <script src="/assets/ckeditor.js"></script>
    <!-- Styles -->

    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-1">Yeni Otel Ekle</h6>
                            <p class="text-sm">Kayıtlı yeni otel ekler.</p>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-3 mt-2">
                                    <div id="image-preview-container">
                                       <div class="alert alert-success text-white" id="preview-text">
                                           Resim eklendiğinde önizleme burada görüntülenecektir.
                                       </div>
                                    </div>

                                </div>
                                <div class="col-9">
                                    <form action="{{ route('hotel-management.store') }}" enctype="multipart/form-data" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Otel Adı</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                                                    @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="location" class="form-label">Konum</label>
                                                    <input type="text" required class="form-control @error('location') is-invalid @enderror" id="location" name="location">
                                                    @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Resim URL</label>
                                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" required name="image">
                                                    @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="total_rooms" class="form-label">Toplam Oda Sayısı</label>
                                                    <input type="number" class="form-control @error('total_rooms') is-invalid @enderror" id="total_rooms" name="total_rooms">
                                                    @error('total_rooms')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contact_email" class="form-label">İletişim E-posta</label>
                                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" required id="contact_email" name="contact_email">
                                                    @error('contact_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contact_phone" class="form-label">İletişim Telefon</label>
                                                    <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" required id="contact_phone" name="contact_phone">
                                                    @error('contact_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3 ">
                                                    <label class="form-check-label" for="is_available">Durum</label>
                                                    <select class="form-control @error('is_available') is-invalid @enderror" required name="is_available" id="is_available">
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Pasif</option>
                                                    </select>
                                                    @error('is_available')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3 ">
                                                    <label class="form-check-label" for="blocked_months">Reservasyonun Kapalı Olacağı Aylar</label>
                                                    <select name="blocked_months" class="form-select" id="multiple-select-field" data-placeholder="Seçiniz" multiple>
                                                        <option value="01">Ocak</option>
                                                        <option value="02">Şubat</option>
                                                        <option value="03">Mart</option>
                                                        <option value="04">Nisan</option>
                                                        <option value="05">Mayıs</option>
                                                        <option value="06">Haziran</option>
                                                        <option value="07">Temmuz</option>
                                                        <option value="08">Ağustos</option>
                                                        <option value="09">Eylül</option>
                                                        <option value="10">Ekim</option>
                                                        <option value="11">Kasım</option>
                                                        <option value="12">Aralık</option>
                                                    </select>
                                                    @error('is_available')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Açıklama</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror"  id="editor" name="description" rows="3"></textarea>
                                                    @error('description')
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
    <!-- Scripts -->
    <script>

        $( '#multiple-select-field' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );

        ClassicEditor
            .create( document.querySelector( '#editor' ))
            .catch( error => {
                console.error( error );
            } );
        document.getElementById('image').addEventListener('change', function (event) {
            var input = event.target;
            var previewContainer = document.getElementById('image-preview-container');
            var previewTextContainer = document.getElementById('preview-text');
            var previewImage = previewContainer.querySelector('img');

            if (previewImage) {
                previewContainer.removeChild(previewImage);
            }

            var file = input.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var newImage = document.createElement('img');
                    newImage.src = e.target.result;
                    newImage.alt = 'Hotel Image';
                    newImage.style.maxWidth = '100%';
                    newImage.style.height = 'auto';
                    newImage.className = 'img-fluid shadow border-radius-xl';
                    if(previewTextContainer) {
                        previewTextContainer.remove();
                    }
                    previewContainer.appendChild(newImage);
                };

                reader.readAsDataURL(file);
            }
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


