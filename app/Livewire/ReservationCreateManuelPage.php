<?php

namespace App\Livewire;

use App\Enums\ReservationStatusEnum;
use App\Jobs\SendIbanSmsJob;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Services\ReservationControlService;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class ReservationCreateManuelPage extends Component
{
    public $user;
    public $users;
    public $form;
    public $canDoReservation = true;
    public $creditCardRedirection = false;
    public $totalPriceToPay;
    public $totalPriceToPayUnformatted;
    public $totalPrice;
    public $room;
    public $guestSize = 1;
    public $check_in_date;
    public $check_out_date;
    public $special_requests;
    public $payment_method = 'bank_transfer';
    public $name;
    public $note;
    public $pan;
    public $Ecom_Payment_Card_ExpDate_Month;
    public $Ecom_Payment_Card_ExpDate_Year;
    public $cvv;
    public $guests = [
        ['name' => null, 'age' => null, 'tc' => null],
    ];
    public $loading = false;
    public $amount;
    public $clientId;
    public $oid;
    public $okUrl;
    public $failUrl;
    public $transactionType;
    public $instalment;
    public $rnd;
    public $storekey;
    public $storetype;
    public $lang;
    public $currencyVal;
    public $hash;

    public $total_price;
    protected $listeners = ['refresh-script', 'resetCheckOutDate'];
    protected $rules = [
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'special_requests' => 'nullable|string',
        'total_price' => 'required',
        'payment_method' => 'required|in:bank_transfer,credit_card',
        'guests' => 'required|array',
        'guests.*.name' => 'required|string|min:3|max:255',
        'guests.*.age' => 'required|numeric|min:0|max:100',
        'guests.*.tc' => 'required|numeric|digits:11',
    ];
    private ReservationControlService $reservationControlService;
    private SmsService $smsService;
    private $reservation;

    public function updatedGuestSize()
    {
        // guests dizisini guestSize'a uygun olarak güncelle
        $this->guests = array_slice($this->guests, 0, $this->guestSize);
        // If the new guest size is smaller than the current size, remove excess elements
        while (count($this->guests) > $this->guestSize) {
            array_pop($this->guests);
        }

        while (count($this->guests) < $this->guestSize) {
            $this->guests[] = ['name' => '', 'age' => '', 'tc' => ''];
        }
        //dd($this->guests);
    }

    public function boot(ReservationControlService $reservationControlService, SmsService $smsService
    ): void
    {
        $this->reservationControlService = $reservationControlService;
        $this->smsService = $smsService;
    }

    public function updatedCheckInDate($value)
    {
        // Giriş tarihi değiştiğinde çıkış tarihini sıfırla
        $this->resetCheckOutDate();

        // Diğer işlemleri gerçekleştir...
    }

    public function resetCheckOutDate()
    {
        // Çıkış tarihini sıfırla
        $this->check_out_date = null;
    }

    public function render()
    {
        $this->canDoReservation = true;
        $this->users = User::query()
                           ->get();

//        if ($this->check_in_date && $this->check_out_date) {
//            $this->totalPriceToPay = moneyFormat(($this->calculateTotalPrice() * 30) / 100);
//            $this->totalPriceToPayUnformatted = ($this->calculateTotalPrice() * 30) / 100;
//            $this->totalPrice = moneyFormat($this->calculateTotalPrice());
//
//
//            $isRoomAvailable = $this->reservationControlService->isRoomAvailable($this->room, $this->check_in_date,
//                                                                                 $this->check_out_date, $this->guests, $this->user);
//
//            if (!$isRoomAvailable['status']) {
//                foreach ($isRoomAvailable['errors'] as $error) {
//                    $this->addError('room_id', $error);
//                }
//                // $this->check_out_date = null;
//                //$this->check_in_date = null;
//                // $this->scriptUpdated();
//            }
//
//
//        } else {
//            $this->totalPriceToPay = moneyFormat(($this->room->price * 30) / 100);
//            $this->totalPriceToPayUnformatted = ($this->room->price * 30) / 100;
//            $this->totalPrice = moneyFormat($this->room->price);
//        }


        $this->guests = array_slice($this->guests, 0, $this->guestSize);
        // If the new guest size is smaller than the current size, remove excess elements
        while (count($this->guests) > $this->guestSize) {
            array_pop($this->guests);
        }

        return view('livewire.reservation-create-manuel-page', [
            'room' => $this->room,
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->dispatch('refresh-script');
    }

//    public function calculateTotalPrice()
//    {
//        $checkInDate = Carbon::parse($this->check_in_date);
//        $checkOutDate = Carbon::parse($this->check_out_date);
//        $totalDayCount = $checkInDate->diffInDays($checkOutDate) == 0 ? 1 : $checkInDate->diffInDays($checkOutDate);
//
//        $basePrice = $this->room->price * $totalDayCount;
//
//
//        // Ek ücretler için başlangıç değeri
//        $extraCharge = 0;
//
//        $under12Count = 0;
//        $between12and18Count = 0;
//        $above18Count = 0;
//
//        // find under 12 age guests
//        foreach ($this->guests as $guest) {
//            if (!isset($guest['age'])) {
//                continue;
//            }
//
//            if ($guest['age'] < 1){
//                $above18Count++;
//            }
//
//            $age = $guest['age'];
//            if ($age < 12) {
//                $under12Count++;
//            }
//
////            if ($age >= 13 && $age < 18) {
////                $between12and18Count++;
////            }
//
//            if ($age >= 13) {
//                $above18Count++;
//            }
//        }
//
//        // 2 den fazla 12 yaşından küçük misafir var ise her misafir başına 0.5 günlük ücret alınır.
//        if ($under12Count > 2) {
////            $extraCharge += ($under12Count - 2) * ($this->room->price / 2);
//            $extraCharge += 0;
//        }
//
//        //  12-18 yaş arası misafir var ise her misafir başına 0.5 ücret alınır.
////        if ($between12and18Count > 0) {
////            $extraCharge += $between12and18Count * ($this->room->price / 2);
////            $extraCharge = $extraCharge * $totalDayCount;
//////            $extraCharge += 0;
////        }
//
//        // 18 yaşından büyük misafir kendisi dışında var ise 1 günlük ücret alınır
//        if ($above18Count > 1) {
////            $extraCharge += ($above18Count - 1) * $this->room->price;
//            $extraCharge += ($above18Count - 1) * ($this->room->price / 2);
//            $extraCharge = $extraCharge * $totalDayCount;
//        }
//
//
//
//        $totalPrice = $basePrice + $extraCharge;
//
//        return $totalPrice;
//    }

    public function save()
    {

        $this->validate();
        if ($this->guestSize != count($this->guests)) {
            $this->addError('guests', 'Kişi bilgileri alanı zorunludur.');
            return;
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $this->total_price)) {
            $this->addError('guests', 'Ödenen tutar formatı hatalı. Virgül değil nokta kullanınız.');
            return;
        }


        try {

            $under18count = 0;
            $above18count = 0;
            foreach ($this->guests as $item) {
                if ($item['age'] < 18) {
                    $under18count++;
                }

                if ($item['age'] >= 18) {
                    $above18count++;
                }


                if ($item['age'] < 1){
                    $this->addError('guests', 'Yaş değeri 0 olamaz');
                    return;
                }

            }


            if ($under18count > 3) {
                $this->addError('guests', '18 yaşından küçük misafir sayısı 3\'ten fazla olamaz.');
                return;
            }

            if ($above18count > 3) {
                $this->addError('guests', '18 yaşından büyük misafir sayısı 3\'ten fazla olamaz.');
                return;
            }

            if ($above18count < 1) {
                $this->addError('guests', '18 yaşından büyük misafir sayısı en az 1 olmalıdır.');
                return;
            }

            if ($this->reservationDuplicateCheck()) {
                return;
            }

            // Kontrol etmek istediğimiz oda bilgisi
            $room = Room::findOrFail($this->room->id);

//            $isRoomAvailable = $this->reservationControlService->isRoomAvailable($room, $this->check_in_date,
//                                                                                 $this->check_out_date, $this->guests, $this->user);

//            if (!$isRoomAvailable['status']) {
//                foreach ($isRoomAvailable['errors'] as $error) {
//                    $this->addError('room_id', $error);
//                }
////                $this->check_out_date = null;
////                $this->check_in_date = null;
////                $this->scriptUpdated();
//                return;
//            }

            $this->createReservation()
                 ->guests()
                 ->createMany($this->guests);


//            if ($this->payment_method == 'bank_transfer') {
//                $user = User::query()
//                            ->where('id', auth()->id())
//                            ->first();
//                SendIbanSmsJob::dispatch($this->smsService, $user);
//            }


        } catch (\Exception $exception) {
            $this->addError('error', 'Bir hata oluştu. Lütfen tekrar deneyiniz.');
            return redirect()
                ->back()
                ->withInput();
        }

        return redirect()
            ->route('reservation.index')
            ->with('success',
                   'Manuel Rezervasyon başarıyla oluşturuldu.');

    }

    public function createForm()
    {

        $url = env('PAYMENT_URL');

//        $form = '<form id="myForm" method="post" action="https://entegrasyon.asseco-see.com.tr/fim/est3Dgate">';
        $form = '<form id="myForm" method="post" action="'.$url.'">';
        $form .= '<input type="text" name="Ecom_Payment_Card_ExpDate_Year" value="'.$this->Ecom_Payment_Card_ExpDate_Year.'" />';
        $form .= '<input type="text" name="Ecom_Payment_Card_ExpDate_Month" value="'.$this->Ecom_Payment_Card_ExpDate_Month.'" />';
        $form .= '<input type="text" name="cc_owner" value="'.$this->name.'" />';
        $form .= '<input type="text" name="cv2" value="'.$this->cvv.'" />';
        $form .= '<input type="text" name="cvv" value="'.$this->cvv.'" />';
        $form .= '<input type="text" name="pan" value="'.$this->pan.'" />';
        $form .= '<input type="text" name="clientid" value="'.$this->clientId.'" />';
        $form .= '<input type="text" name="amount" value="'.$this->amount.'" />';
        $form .= '<input type="text" name="islemtipi" value="'.$this->transactionType.'" />';
        $form .= '<input type="text" name="taksit" value="'.$this->instalment.'" />';
        $form .= '<input type="text" name="oid" value="'.$this->oid.'" />';
        $form .= '<input type="text" name="okUrl" value="'.$this->okUrl.'" />';
        $form .= '<input type="text" name="failUrl" value="'.$this->failUrl.'" />';
        $form .= '<input type="text" name="rnd" value="'.$this->rnd.'" />';
        $form .= '<input type="text" name="hash" value="'.$this->hash.'" />';
        $form .= '<input type="text" name="storetype" value="'.$this->storetype.'" />';
        $form .= '<input type="text" name="lang" value="'.$this->lang.'" />';
        $form .= '<input type="text" name="currency" value="'.$this->currencyVal.'" />';
        $form .= '</form>';

        return $form;
    }

    public function validateCreditCard()
    {
        $this->validate([
                            'pan' => 'required|numeric|digits:16',
                            'Ecom_Payment_Card_ExpDate_Month' => 'required|numeric|digits:2',
                            'Ecom_Payment_Card_ExpDate_Year' => 'required|numeric|digits:2',
                            'cvv' => 'required|numeric|digits:3',
                        ]);
    }

    private function reservationDuplicateCheck(): bool
    {
        $exists = Reservation::query()
                             ->where('room_id', $this->room->id)
                             ->where('user_id', $this->user)
                             ->where(function ($query) {
                                 $query->whereBetween('check_in_date', [
                                     $this->check_in_date,
                                     $this->check_out_date
                                 ])
                                       ->orWhereBetween('check_out_date', [
                                           $this->check_in_date,
                                           $this->check_out_date
                                       ])
                                       ->orWhere(function ($query) {
                                           $query->where('check_in_date', '<=', $this->check_in_date)
                                                 ->where('check_out_date', '>=', $this->check_out_date);
                                       });
                             })
                             ->whereIn('reservation_status', [
                                 ReservationStatusEnum::Pending->name,
                                 ReservationStatusEnum::Success->name
                             ])
                             ->exists();


        if ($exists) {
            $this->addError('error', 'Bu oda için rezervasyonunuz bulunmaktadır.');
            return true;
        }

        return false;
    }



    private function createReservation(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {

        $checkInDate = Carbon::parse($this->check_in_date);
        $checkOutDate = Carbon::parse($this->check_out_date);
        $totalDayCount = $checkInDate->diffInDays($checkOutDate);

        $totalPriceToPay = $this->total_price;


        $reservation = Reservation::query()
                                  ->create([
                                               'room_id' => $this->room->id,
                                               'user_id' =>  auth()->user()->id,
                                               'number_of_guests' => $this->guestSize,
                                               'check_in_date' => $this->check_in_date,
                                               'check_out_date' => $this->check_out_date,
                                               'special_requests' => $this->special_requests,
                                               'payment_method' => $this->payment_method,
                                               'reservation_status' => ReservationStatusEnum::Success->name,
                                               'total_amount' => $totalPriceToPay,
                                               'paid_amount' => 0,
                                               'bank_transfer_code' => null,
                                               'transaction_id' => Str::uuid(),
                                               'payment_status' => $this->payment_method == 'credit_card' ? 0 :
                                                   1,
                                           ]);

        $this->reservation = $reservation;

        return $reservation;

    }



}
