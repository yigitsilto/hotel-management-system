<div>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Ek kütüphane bağlantıları -->

    <style>
        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 99999999999999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

            background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 150ms infinite linear;
            -moz-animation: spinner 150ms infinite linear;
            -ms-animation: spinner 150ms infinite linear;
            -o-animation: spinner 150ms infinite linear;
            animation: spinner 150ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>


        <form wire:submit="save">

        <div wire:loading class="loading">Loading&#8230;</div>


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
                                Bugün Ödenmesi Gereken Ücret: <span
                                        class="font-weight-bold">{{$totalPriceToPay}}</span>
                            </p>

                            <p class="mb-0 text-md">
                                Toplam Ücret: <span
                                        class="font-weight-bold">{{$totalPrice}}</span>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                                <li class="nav-item">

                                    @if($canDoReservation)
                                        <button style="width: 100%" class="nav-link mb-0 px-0 py-1 active "
                                                href="{{route('user-reservation.createReservation', $room->id)}}" role="tab"
                                                aria-selected="true">
                                            <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42"
                                                 version="1.1"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF"
                                                       fill-rule="nonzero">
                                                        <g transform="translate(1716.000000, 291.000000)">
                                                            <g transform="translate(603.000000, 0.000000)">
                                                                <path class="color-background"
                                                                      d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                                </path>
                                                                <path class="color-background"
                                                                      d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"
                                                                      opacity="0.7"></path>
                                                                <path class="color-background"
                                                                      d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"
                                                                      opacity="0.7"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                            <span class="ms-1">Ödemeyi Onayla</span>
                                        </button>

                                    @else

                                        <button type="button" style="width: 100%" class="nav-link mb-0 px-0 py-1 active " role="tab"
                                                aria-selected="true">
                                            <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42"
                                                 version="1.1"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF"
                                                       fill-rule="nonzero">
                                                        <g transform="translate(1716.000000, 291.000000)">
                                                            <g transform="translate(603.000000, 0.000000)">
                                                                <path class="color-background"
                                                                      d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                                </path>
                                                                <path class="color-background"
                                                                      d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"
                                                                      opacity="0.7"></path>
                                                                <path class="color-background"
                                                                      d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"
                                                                      opacity="0.7"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                            <span class="ms-1">Reservasyon Yapılamaz</span>
                                        </button>
                                    @endif


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
                                        <input type="date" class="form-control" id="check_in_date"
                                               name="check_in_date" wire:model.live="check_in_date" wire:ignore.self>
                                        @error('check_in_date') <span
                                                class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Çıkış Tarihi -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_out_date">Çıkış Tarihi:</label>
                                        <input type="date" disabled class="form-control" id="check_out_date"
                                               name="check_out_date" wire:model.live="check_out_date" wire:ignore.self>
                                        @error('check_out_date') <span
                                                class="text-danger">{{ $message }}</span> @enderror
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
                                        <input type="text" class="form-control" id="note" name="special_requests"
                                               wire:model="special_requests">
                                        @error('special_requests') <span
                                                class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                @if($guestSize > 0)
                                    <h6>Kişi Bilgileri</h6>
                                @endif

                                @for ($i = 0; $i < $guestSize; $i++)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="guest_name_{{ $i }}">{{$i + 1}}. Kişi İsim Soyisim:</label>
                                            <input type="text" class="form-control" wire:model="guests.{{ $i }}.name"
                                                   id="guest_name_{{ $i }}"
                                                   name="guests.{{ $i }}.name"/>
                                            @error('guests.'.$i.'.name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="guest_tc_{{ $i }}">{{$i + 1}}. Kişi T.C:</label>
                                            <input type="number" class="form-control" wire:model="guests.{{ $i }}.tc"
                                                   id="guest_tc_{{ $i }}"
                                                   name="guests.{{ $i }}.tc"/>
                                            @error('guests.'.$i.'.tc')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="guest_age_{{ $i }}">{{$i + 1}}. Kişi Yaş:</label>
                                                <input type="number" class="form-control" wire:model="guests.{{ $i }}.age"
                                                       id="guest_age_{{ $i }}"
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
                                    <select wire:model.live="payment_method" class="form-control" name="payment_method"
                                            id="payment_method">
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
                                            <input type="text" class="form-control" id="name" name="name"
                                                   wire:model="name">
                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Kart Numarası -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="pan">Kart Numarası</label>
                                            <input type="text" class="form-control" id="pan"
                                                   name="pan" wire:model="pan">
                                            @error('pan') <span
                                                    class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Ay -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Ecom_Payment_Card_ExpDate_Month">Ay</label>
                                            <select class="form-control"  wire:model="Ecom_Payment_Card_ExpDate_Month">
                                                <option value="" selected>Seçiniz</option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                            @error('Ecom_Payment_Card_ExpDate_Month') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Yıl -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="year">Yıl</label>
                                            <select class="form-control" id="Ecom_Payment_Card_ExpDate_Year" wire:model="Ecom_Payment_Card_ExpDate_Year">
                                                <option value="" selected>Seçiniz</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                                <option value="32">32</option>
                                                <option value="33">33</option>
                                            </select>
                                            @error('Ecom_Payment_Card_ExpDate_Year') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- CVV -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cvv">CVV</label>
                                            <input type="text" class="form-control" id="cvv" name="cvv"
                                                   wire:model="cvv">
                                            @error('cvv') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
{{--                                    <button type="submit" class="btn btn-primary">Submit</button>--}}
                                </div>

                        </div>
                        @endif


                    </div>
                </div>
            </div>


            <!-- Payment area ending -->


        </div>
</form>

    <form id="myForm" method="post" action="https://entegrasyon.asseco-see.com.tr/fim/est3Dgate">
{{--            <form id="myForm" method="post" action=" http://rezervasyon.piza.com.tr/fail-payment">--}}
        <input type="hidden" name="Ecom_Payment_Card_ExpDate_Year" wire:model="Ecom_Payment_Card_ExpDate_Year"/>
        <input type="hidden" name="Ecom_Payment_Card_ExpDate_Month" wire:model="Ecom_Payment_Card_ExpDate_Month"/>
        <input type="hidden" name="cc_owner" wire:model="name"/>
        <input type="hidden" name="cv2" wire:model="cvv"/>
        <input type="hidden" name="cvv" wire:model="cvv"/>
        <input type="hidden" name="pan" wire:model="pan"/>
        <input type="hidden" name="clientid" wire:model="clientId"/>
        <input type="hidden" name="amount" wire:model="amount"/>
        <input type="hidden" name="islemtipi" wire:model="transactionType"/>
        <input type="hidden" name="taksit" wire:model="instalment"/>
        <input type="hidden" name="oid" wire:model="oid"/>
        <input type="hidden" name="okUrl" wire:model="okUrl"/>
        <input type="hidden" name="failUrl" wire:model="failUrl"/>
        <input type="hidden" name="rnd" wire:model="rnd"/>
        <input type="hidden" name="hash" wire:model="hash"/>
        <input type="hidden" name="storetype" wire:model="storetype"/>
        <input type="hidden" name="lang" wire:model="lang"/>
        <input type="hidden" name="currency" wire:model="currencyVal"/>

    </form>

{{--    <form id="myForm" method="post" action=" https://entegrasyon.asseco-see.com.tr/fim/est3Dgate">--}}
{{--        --}}{{--    <form id="myForm" method="post" action=" http://rezervasyon.piza.com.tr/fail-payment">--}}
{{--        <input type="text" name="Ecom_Payment_Card_ExpDate_Year" wire:model="Ecom_Payment_Card_ExpDate_Year"/>--}}
{{--        <input type="text" name="Ecom_Payment_Card_ExpDate_Month" wire:model="Ecom_Payment_Card_ExpDate_Month"/>--}}
{{--        <input type="text" name="pan" wire:model="pan"/>--}}
{{--        <input type="text" name="clientid" wire:model="clientId"/>--}}
{{--        <input type="text" name="amount" wire:model="amount"/>--}}
{{--        <input type="text" name="islemtipi" wire:model="transactionType"/>--}}
{{--        <input type="text" name="taksit" wire:model="instalment"/>--}}
{{--        <input type="text" name="oid" wire:model="oid"/>--}}
{{--        <input type="text" name="okUrl" wire:model="okUrl"/>--}}
{{--        <input type="text" name="failUrl" wire:model="failUrl"/>--}}
{{--        <input type="text" name="rnd" wire:model="rnd"/>--}}
{{--        <input type="text" name="hash" wire:model="hash"/>--}}
{{--        <input type="text" name="storetype" wire:model="storetype"/>--}}
{{--        <input type="text" name="lang" wire:model="lang"/>--}}
{{--        <input type="text" name="currency" wire:model="currencyVal"/>--}}
{{--        <input type="text" name="refreshtime" value="100"/>--}}

{{--        <button type="submit" class="btn btn-primary">Submit</button>--}}

{{--    </form>--}}

    <script>

        document.addEventListener('creditCardRedirection', function () {
            setTimeout(function () {
                submitForm();
            }, 1500);
        });

        // JavaScript
        function submitForm() {
            document.getElementById("myForm").submit();
        }

        function initDate() {
            var checkInDateInput = document.getElementById("check_in_date");
            var checkOutDateInput = document.getElementById("check_out_date");

            // Bugünden önceki tarihleri pasif yapmak için
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;

            checkInDateInput.setAttribute("min", today);
            checkOutDateInput.setAttribute("min", today);

            checkInDateInput.addEventListener("change", function () {
                var checkInDate = new Date(this.value);
                var checkOutDate = new Date(checkOutDateInput.value);

                if (checkInDate > checkOutDate) {
                    checkOutDateInput.value = this.value;
                }

                checkOutDateInput.setAttribute("min", this.value);
                checkOutDateInput.disabled = false;
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            initDate();
        });

        window.addEventListener('refresh-script', event => {
            initDate();
        })
    </script>
</div>