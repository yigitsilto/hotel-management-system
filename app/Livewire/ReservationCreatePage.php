<?php

namespace App\Livewire;

use App\Enums\ReservationStatusEnum;
use App\Jobs\SendIbanSmsJob;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Services\EstPosService;
use App\Services\ReservationControlService;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class ReservationCreatePage extends Component
{
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
    public $guests = [];
    public $loading = false;
    protected $listeners = ['refresh-script'];
    protected $rules = [
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'special_requests' => 'nullable|string',
        'payment_method' => 'required|in:bank_transfer,credit_card',
        'guests' => 'required|array',
        'guests.*.name' => 'required|string',
        'guests.*.age' => 'required|numeric',
        'guests.*.tc' => 'required|numeric|digits:11',
    ];
    private ReservationControlService $reservationControlService;
    private SmsService $smsService;


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


    private $reservation;




    public function boot(ReservationControlService $reservationControlService, SmsService $smsService
    ): void
    {
        $this->reservationControlService = $reservationControlService;
        $this->smsService = $smsService;
    }

    public function render()
    {
        $this->canDoReservation = true;

        if ($this->check_in_date && $this->check_out_date) {
            $this->totalPriceToPay = moneyFormat(($this->calculateTotalPrice() * 30) / 100);
            $this->totalPriceToPayUnformatted = ($this->calculateTotalPrice() * 30) / 100;
            $this->totalPrice = moneyFormat($this->calculateTotalPrice());
        } else {
            $this->totalPriceToPay = moneyFormat(($this->room->price * 30) / 100);
            $this->totalPriceToPayUnformatted = ($this->room->price * 30) / 100;
            $this->totalPrice = moneyFormat($this->room->price);
        }
        return view('livewire.reservation-create-page', [
            'room' => $this->room,
        ]);
    }

    public function calculateTotalPrice()
    {
        $checkInDate = Carbon::parse($this->check_in_date);
        $checkOutDate = Carbon::parse($this->check_out_date);
        $totalDayCount = $checkInDate->diffInDays($checkOutDate) == 0 ? 1 : $checkInDate->diffInDays($checkOutDate);

        $price = $this->room->price * $totalDayCount;

        return $price;
    }

    public function sendFormData()
    {
        // Endpoint URL
        $endpoint = "https://entegrasyon.asseco-see.com.tr/fim/est3Dgate";

        // Form verileri
        $postData = [
            'Ecom_Payment_Card_ExpDate_Year' => $this->Ecom_Payment_Card_ExpDate_Year,
            'Ecom_Payment_Card_ExpDate_Month' => $this->Ecom_Payment_Card_ExpDate_Month,
            'pan' => $this->pan,
            'clientid' => $this->clientId,
            'amount' => $this->amount,
            'islemtipi' => $this->transactionType,
            'taksit' => $this->instalment,
            'oid' => $this->oid,
            'okUrl' => $this->okUrl,
            'failUrl' => $this->failUrl,
            'rnd' => $this->rnd,
            'hash' => $this->hash,
            'storetype' => $this->storetype,
            'lang' => $this->lang,
            'currency' => $this->currencyVal,
            'refreshtime' => 100,
        ];


        // Curl ayarları
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Curl işlemini gerçekleştir
        $response = curl_exec($ch);

        // Curl işlemini kapat
        curl_close($ch);


        // Curl isteği başarısız ise hata döndür
        if ($response === false) {
            die('POST isteği başarısız oldu: ' . curl_error($ch));
        }

        // İstek başarılıysa dönen veriyi incele
    }


    public function validateCreditCard() {
        $this->validate([
            'pan' => 'required|numeric|digits:16',
            'Ecom_Payment_Card_ExpDate_Month' => 'required|numeric|digits:2',
            'Ecom_Payment_Card_ExpDate_Year' => 'required|numeric|digits:2',
            'cvv' => 'required|numeric|digits:3',
        ]);
    }


    public function save()
    {

        if ($this->payment_method == 'credit_card') {
            // do the validations for credit card
            $this->validateCreditCard();
        }
        $this->validate();

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

            $isRoomAvailable = $this->reservationControlService->isRoomAvailable($room, $this->check_in_date,
                                                                                 $this->check_out_date);

            if (!$isRoomAvailable['status']) {
                foreach ($isRoomAvailable['errors'] as $error) {
                    $this->addError('room_id', $error);
                }
                $this->check_out_date = null;
                $this->check_in_date = null;
                $this->scriptUpdated();
                return;
            }

            $this->createReservation()
                 ->guests()
                 ->createMany($this->guests);


            if ($this->payment_method == 'bank_transfer') {
                $user = User::query()
                            ->where('id', auth()->id())
                            ->first();
                SendIbanSmsJob::dispatch($this->smsService, $user);
            }

            if ($this->payment_method == 'credit_card') {
                $this->getForm();
                $this->amount = $this->totalPriceToPayUnformatted;
                $this->oid = $this->reservation->id;
                $this->creditCardRedirection = true;
                $this->dispatch('creditCardRedirection');
                return;
            }

        } catch (\Exception $exception) {
            $this->addError('error', 'Bir hata oluştu. Lütfen tekrar deneyiniz.');
            return redirect()
                ->back()
                ->withInput();
        }

        return redirect()
            ->route('user-reservation.myReservations')
            ->with('success',
                   'Rezervasyonunuz başarıyla oluşturuldu. Havale işlemleri için 10 dakika içinde göndermemeniz durumunda rezervasyonunuz iptal edilecektir.');

    }

    private function reservationDuplicateCheck(): bool
    {
        $exists = Reservation::query()
                             ->where('room_id', $this->room->id)
                             ->where('user_id', auth()->id())
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

    public function scriptUpdated()
    {
        $this->dispatchBrowserEvent('refresh-script');
    }

    private function createReservation(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        $paidAmount = 0;
        // TODO kredi kartıysa ödeme işlemleri yapılacak
        if ($this->payment_method == 'credit_card') {

        }
        $checkInDate = Carbon::parse($this->check_in_date);
        $checkOutDate = Carbon::parse($this->check_out_date);
        $totalDayCount = $checkInDate->diffInDays($checkOutDate);

        $totalPriceToPay = (($this->room->price * $totalDayCount) * 30) / 100;


        $reservation = Reservation::query()
                                  ->create([
                                               'room_id' => $this->room->id,
                                               'user_id' => auth()->id(),
                                               'number_of_guests' => $this->guestSize,
                                               'check_in_date' => $this->check_in_date,
                                               'check_out_date' => $this->check_out_date,
                                               'special_requests' => $this->special_requests,
                                               'payment_method' => $this->payment_method,
                                               'reservation_status' => ReservationStatusEnum::Pending->name,
                                               'total_amount' => $totalPriceToPay,
                                               'paid_amount' => $paidAmount,
                                               'transaction_id' => Str::uuid(),
                                           ]);

        $this->reservation = $reservation;

        return $reservation;

    }



    public function getForm()
    {
        // Değerleri başlangıçta ayarla
        $this->clientId = config('payment.client_id');
        $this->okUrl = config('payment.ok_url');
        $this->failUrl = config('payment.fail_url');
        $this->transactionType = config('payment.transaction_type');
        $this->instalment = 1;
        $this->rnd = microtime();
        $this->storekey = config('payment.store_key');
        $this->storetype = config('payment.store_type');
        $this->lang = config('payment.lang');
        $this->currencyVal = config('payment.currency');


        // Hash değerini hesapla
        $hashstr = $this->clientId.$this->oid.$this->amount.$this->okUrl.$this->failUrl.$this->transactionType.$this->instalment.$this->rnd.$this->storekey;
        $this->hash = base64_encode(pack('H*', sha1($hashstr)));

//        return view(
//            'payment::payment',
//            compact(
//                'clientId',
//                'amount',
//                'oid',
//                'okUrl',
//                'failUrl',
//                'transactionType',
//                'instalment',
//                'rnd',
//                'storekey',
//                'storetype',
//                'lang',
//                'currencyVal',
//                'hash'
//            )
//        );
    }



}
