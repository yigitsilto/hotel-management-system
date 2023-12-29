<div>
    <form wire:submit="save">


    <div class="container-fluid">
        <div class="page-header min-height-100 border-radius-xl mt-4">

        </div>
        <div class="card card-body blur shadow-blur  mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{asset($room->base_image)}}" alt="profile_image"
                             class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{$room->title}}
                        </h5>
                        <p class="mb-0 text-md">
                            Toplam Ödenmesi Gereken Ücret: <span class="font-weight-bold">{{moneyFormat($room->price)}}</span>
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <li class="nav-item">
                                <button style="width: 100%" class="nav-link mb-0 px-0 py-1 active " href="{{route('user-reservation.createReservation', $room->id)}}" role="tab" aria-selected="true">
                                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(603.000000, 0.000000)">
                                                        <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                        </path>
                                                        <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                        <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="ms-1">Ödemeyi Onayla</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-12 col-xl-6">

                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">

                            </div>

                        </div>
                    </div>
                    <div class="card-body p-3">
                       <div class="row">
                           <!-- Giriş Tarihi -->
                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="check_in_date">Giriş Tarihi:</label>
                                   <input type="datetime-local" class="form-control" id="check_in_date" name="check_in_date" wire:model="check_in_date">
                                   @error('check_in_date') <span class="text-danger">{{ $message }}</span> @enderror
                               </div>
                           </div>

                           <!-- Çıkış Tarihi -->
                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="check_out_date">Çıkış Tarihi:</label>
                                   <input type="datetime-local" class="form-control" id="check_out_date" name="check_out_date" wire:model="check_out_date">
                                   @error('check_out_date') <span class="text-danger">{{ $message }}</span> @enderror
                               </div>
                           </div>

                           <!-- Kişi Sayısı -->
                           <div class="col-md-6">
                               <label for="guestSize">Kişi Sayısı:</label>
                               <select class="form-control" id="guestSize" wire:model.live="guestSize">
                                   @for ($i = 1; $i <= $room->capacity; $i++)
                                       <option value="{{ $i }}">{{ $i }}</option>
                                   @endfor
                               </select>
                               @error('guestSize') <span class="text-danger">{{ $message }}</span> @enderror
                           </div>

                           <!-- Not -->
                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="special_requests">Not:</label>
                                   <input type="text" class="form-control" id="note" name="special_requests" wire:model="special_requests">
                                   @error('special_requests') <span class="text-danger">{{ $message }}</span> @enderror
                               </div>
                           </div>
                       </div>


                        <div class="row">
                            @if($guestSize > 0)
                                <h6>Kişi Bilgileri</h6>
                            @endif

                                @for ($i = 0; $i < $guestSize; $i++)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="guest_name_{{ $i }}">{{$i + 1}}. Kişi Adı:</label>
                                            <input type="text" class="form-control" wire:model="guests.{{ $i }}.name" id="guest_name_{{ $i }}"
                                                   name="guests.{{ $i }}.name"/>
                                            @error('guests.'.$i.'.name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="guest_age_{{ $i }}">{{$i + 1}}. Kişi Yaşı:</label>
                                            <input type="number" class="form-control" wire:model="guests.{{ $i }}.age" id="guest_age_{{ $i }}"
                                                   name="guests.{{ $i }}.age"/>
                                            @error('guests.'.$i.'.age')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endfor

                        </div>


                    </div>
                </div>
            </div>


            <!-- Payment area -->

            <div class="col-12 col-xl-6">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">

                            </div>

                        </div>
                    </div>
                    <div class="card-body p-3">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="payment_method">Ödeme Tipi</label>
                                <select wire:model.live="payment_method" class="form-control" name="payment_method" id="payment_method">
                                    <option value="bank_transfer">Havale</option>
                                    <option value="credit_card">Kredi Kartı</option>
                                </select>
                            </div>
                        </div>

                        @if($payment_method == 'bank_transfer')


                                <div>
                                    <h3 style="text-align: center">Ziraat Bankası</h3>
                                    <p style="text-align: center">Hesap No: 1231211HBSS</p>
                                    <p style="text-align: center">TR12312391293912391291239</p>
                                </div>

                        @endif


                       @if($payment_method == 'credit_card')

                            <div class="row">


                                <!-- Kart Üzerinde Yazan İsim Soyisim -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Kart Üzerinde Yazan İsim Soyisim</label>
                                        <input type="text" class="form-control" id="name" name="name" wire:model="name">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Kart Numarası -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="credit_number">Kart Numarası</label>
                                        <input type="text" class="form-control" id="credit_number" name="credit_number" wire:model="credit_number">
                                        @error('credit_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Ay -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="month">Ay</label>
                                        <select class="form-control" name="month" id="month" wire:model="month">
                                            <option>01</option>
                                        </select>
                                        @error('month') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Yıl -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="year">Yıl</label>
                                        <select class="form-control" name="year" id="year" wire:model="year">
                                            <option>2024</option>
                                        </select>
                                        @error('year') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- CVV -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cvv">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" wire:model="cvv">
                                        @error('cvv') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                </div>

                            </div>
                       @endif


                    </div>
                </div>
            </div>


            <!-- Payment area ending -->


        </div>
    </div>
    </form>
</div>