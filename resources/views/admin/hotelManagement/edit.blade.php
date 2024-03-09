@extends('layouts.admin')
@section('title', 'Anasayfa')
@section('content')
    <script src="/assets/ckeditor.js"></script>

    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card mb-4">

                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-1">Kayıt Bilgisi Düzenle</h6>
                            <p class="text-sm">Kayıtlı otel bilgisini düzenler.</p>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 col-sm-12 mt-2">
                                    <div id="image-preview-container">
                                        <img src="{{ asset($hotel->image) }}" alt="img-blur-shadow"
                                             style="width: 100%; height: 150px; object-fit: cover"
                                             id="image-preview" class="img-fluid shadow border-radius-xl">
                                    </div>

                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-12">
                                    <form action="{{ route('hotel-management.update', $hotel->id) }}"
                                          enctype="multipart/form-data" method="post">
                                        @csrf
                                        {{ method_field('PUT')}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Otel Adı</label>
                                                    <input type="text"
                                                           class="form-control @error('name') is-invalid @enderror"
                                                           id="name" name="name" required
                                                           value="{{ old('name', $hotel->name) }}">
                                                    @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="location" class="form-label">Konum</label>
                                                    <input type="text" required
                                                           class="form-control @error('location') is-invalid @enderror"
                                                           id="location" name="location"
                                                           value="{{ old('location', $hotel->location) }}">
                                                    @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Resim (Değiştirmek
                                                        istemiyorsanız boş bırakınız.)</label>
                                                    <input type="file"
                                                           class="form-control @error('image') is-invalid @enderror"
                                                           id="image" name="image">
                                                    @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="total_rooms" class="form-label">Toplam Oda
                                                        Sayısı</label>
                                                    <input type="number"
                                                           class="form-control @error('total_rooms') is-invalid @enderror"
                                                           id="total_rooms" name="total_rooms"
                                                           value="{{ old('total_rooms', $hotel->total_rooms) }}">
                                                    @error('total_rooms')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contact_email" class="form-label">İletişim
                                                        E-posta</label>
                                                    <input type="email"
                                                           class="form-control @error('contact_email') is-invalid @enderror"
                                                           required id="contact_email" name="contact_email"
                                                           value="{{ old('contact_email', $hotel->contact_email) }}">
                                                    @error('contact_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contact_phone" class="form-label">İletişim
                                                        Telefon</label>
                                                    <input type="tel"
                                                           class="form-control @error('contact_phone') is-invalid @enderror"
                                                           required id="contact_phone" name="contact_phone"
                                                           value="{{ old('contact_phone', $hotel->contact_phone) }}">
                                                    @error('contact_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="editor" class="form-label">Açıklama</label>
                                                    <textarea
                                                            class="form-control @error('description') is-invalid @enderror"
                                                             id="editor" name="description"
                                                            rows="3">{{ old('description', $hotel->description) }}</textarea>
                                                    @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="is_available">Durum</label>
                                                    <select class="form-control @error('is_available') is-invalid @enderror"
                                                            required name="is_available" id="is_available">
                                                        <option value="1" {{ old('is_available', $hotel->is_available) == 1 ? 'selected' : '' }}>
                                                            Aktif
                                                        </option>
                                                        <option value="0" {{ old('is_available', $hotel->is_available) == 0 ? 'selected' : '' }}>
                                                            Pasif
                                                        </option>
                                                    </select>
                                                    @error('is_available')
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
        ClassicEditor
            .create( document.querySelector( '#editor' ))
            .catch( error => {
                console.error( error );
            } );
        document.getElementById('image').addEventListener('change', function (event) {
            var input = event.target;
            var previewContainer = document.getElementById('image-preview-container');
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


