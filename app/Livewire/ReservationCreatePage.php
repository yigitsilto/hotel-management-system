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

    public $totalPriceToPay;
    public $totalPriceToPayUnformatted;
    public $totalPrice;
    public $room;
    public $guestSize = 1;
    public $check_in_date;
    public $check_out_date;
    public $special_requests;
    public $payment_method = 'bank_transfer';
    public $name = "asdasd asdasd";
    public $note;
    public $pan = '4531444531442283';
    public $Ecom_Payment_Card_ExpDate_Month = '12';
    public $Ecom_Payment_Card_ExpDate_Year = '2026';
    public $cvv = '001';
    public $guests = [];
    public $loading = false;
    protected $listeners = ['refresh-script'];
    protected $rules = [
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'special_requests' => 'nullable|string',
        'payment_method' => 'required|in:bank_transfer,credit_card',
        'name' => 'nullable|string',
        'credit_number' => 'nullable|numeric|digits_between:13,19',
        'month' => 'nullable|digits:2',
        'year' => 'nullable|digits:4',
        'cvv' => 'nullable|numeric|digits:3',
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

        if ($this->payment_method == 'credit_card') {
           $this->getForm();
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


    public function save()
    {



        $this->paymentProcess();


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
                return false;
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

        return Reservation::query()
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

    }


    private function paymentProcess(){
        $this->getForm();
        // Burada form verilerini kaydedebilirsiniz.

        // Şimdi POST isteğini NestPay API'larına gönderin

        $postData = [
            'clientid' => $this->clientId,
            'amount' => 120.0,
            'islemtipi' => $this->transactionType,
            'taksit' => 1,
            'oid' => $this->oid,
            'okUrl' => $this->okUrl,
            'failUrl' => $this->failUrl,
            'rnd' => $this->rnd,
            'hash' => $this->hash,
            'storetype' => $this->storetype,
            'lang' => $this->lang,
            'currency' => $this->currencyVal,
            'refreshtime' => 100,
            'name' => $this->name,
            'pan' => $this->pan,
            'Ecom_Payment_Card_ExpDate_Month' => $this->Ecom_Payment_Card_ExpDate_Month,
            'Ecom_Payment_Card_ExpDate_Year' => $this->Ecom_Payment_Card_ExpDate_Year,
            'cvv' => $this->cvv,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://entegrasyon.asseco-see.com.tr/fim/est3Dgate');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        dd($response);

        if ($response === false) {
            die('POST isteği başarısız oldu: ' . curl_error($ch));
        }
    }


    public function getForm()
    {
        $amount = $this->totalPriceToPayUnformatted;

        // Değerleri başlangıçta ayarla
        $this->amount = $this->totalPriceToPayUnformatted;
        $this->clientId = config('payment.client_id');
        $this->oid = 111; // Order Id. This can be generated dynamically if needed.
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
