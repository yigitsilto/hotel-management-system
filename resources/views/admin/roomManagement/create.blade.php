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
                            <h6 class="mb-1">{{$hotel->name}} Otele Yeni Oda Kayıdı Ekle</h6>
                            <p class="text-sm">Kayıtlı otele yeni oda ekler.</p>
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
                                    <form action="{{ route('room-management.store', $hotel->id) }}" enctype="multipart/form-data" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Oda Başlığı</label>
                                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                                                    @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="location" class="form-label">Kapak Resmi</label>
                                                    <input type="file" required class="form-control @error('image') is-invalid @enderror" id="image"  name="image">
                                                    @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="room_type" class="form-label">Oda Tipi</label>
                                                    <select class="form-control @error('room_type') is-invalid @enderror" id="room_type" name="room_type" required>
                                                        @foreach(\App\Enums\RoomTypeEnum::getValues() as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('room_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="capacity" class="form-label">Kapasite(Bir odada kaç kişi kalabilir)</label>
                                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" required name="capacity">
                                                    @error('capacity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Günlük Fiyat</label>
                                                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" required name="price">
                                                    @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="same_room_count" class="form-label">Aynı odadan kaç tane var ?</label>
                                                    <input type="number" class="form-control @error('same_room_count') is-invalid @enderror" id="same_room_count" required name="same_room_count">
                                                    @error('same_room_count')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="max_person" class="form-label">Maksimum Kişi Sayısı Bir Oda İçin</label>
                                                    <input type="number" class="form-control @error('max_person') is-invalid @enderror" id="max_person" required name="max_person">
                                                    @error('max_person')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="editor" class="form-label">Açıklama</label>
                                                <textarea class="form-control  @error('description') is-invalid @enderror" id="editor" name="description" rows="3"></textarea>
                                                @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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


