@extends('layouts.admin')
@section('title', 'Anasayfa')
@section('content')
    <style>
        body {
            padding: 20px;
        }
        #img{
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .image-area {
            position: relative;
            width: 100%;
        }
        .image-area img{
            max-width: 100%;
            height: auto;
        }
        .remove-image {
            display: none;
            position: absolute;
            top: -10px;
            right: -10px;
            border-radius: 10em;
            padding: 2px 6px 3px;
            text-decoration: none;
            font: 700 21px/20px sans-serif;
            background: #555;
            border: 3px solid #fff;
            color: #FFF;
            box-shadow: 0 2px 6px rgba(0,0,0,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            -webkit-transition: background 0.5s;
            transition: background 0.5s;
        }
        .remove-image:hover {
            background: #E54E4E;
            padding: 3px 7px 5px;
            top: -11px;
            right: -11px;
        }
        .remove-image:active {
            background: #E54E4E;
            top: -10px;
            right: -11px;
        }
    </style>
    <script src="/assets/dropzone.js"></script>
    <link rel="stylesheet" href="/assets/dropzone.css" type="text/css" />

    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-1">{{$room->title}}  Odaya Resim ekle</h6>
                            <p class="text-sm">Kayıtlı odaya resim ekler.</p>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-12">
                                    <form action="{{route('room-management.upload.images', $room->id)}}" class="dropzone" id="myDropzone">
                                        @csrf
                                    </form>
                                    <br>
                                  <div class="row">
                                      <div class="col-6" style="display: flex; justify-content: center; flex: 1">
                                          <a href="{{route('room-management.upload.images.create', $room->id)}}" class="btn btn-outline-primary">Kaydet Ve Ekranda Kal</a>
                                      </div>
                                      <div class="col-6" style="display: flex; justify-content: center; flex: 1">
                                          <a href="{{route('hotel-management.show', $room->hotel->id)}}" class="btn btn-outline-primary">Kaydet Ve Geri Dön</a>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        @foreach($medias as $media)
                                            <div class="col-lg-3 col-md-6 mt-2">
                                                <div class="image-area">
                                                    <img src="{{$media->getUrl()}}" id="img"  alt="Preview">
                                                    <form action="{{route('room-management.delete.images', ['room' => $room->id, 'media' => $media])}}" method="POST">
                                                        @csrf
                                                        <button class="remove-image" href="#" style="display: inline;">&#215;</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
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

        // Dropzone configuration
        Dropzone.options.myDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            acceptedFiles: "image/*", // Allow only images
            dictDefaultMessage: "Yüklemek istediğiniz resimleri buraya sürükleyin veya tıklayın.",
            addRemoveLinks: false, // Show remove link on each image preview
            init: function() {
                this.on("success", function(file, response) {
                    // Handle successful uploads
                    console.log("File uploaded successfully:", response);
                });
                this.on("removedfile", function(file) {
                    // Handle file removal
                    console.log("File removed:", file);
                });
            }
        };

    </script>
@endsection


