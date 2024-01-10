<?php

namespace App\Livewire;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\ReservationControlService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class ReservationCreatePage extends Component
{
    protected $listeners = ['refresh-script'];

    public $canDoReservation = true;
    public $totalPriceToPay;
    public $totalPrice;
    public $room;
    public $guestSize = 1;
    public $check_in_date;
    public $check_out_date;
    public $special_requests;
    public $payment_method = 'bank_transfer';
    public $name;
    public $note;
    public $credit_number;
    public $month;
    public $year;
    public $cvv;
    public $guests = [];
    public $loading = false;
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

    public function boot(ReservationControlService $reservationControlService): void
    {
        $this->reservationControlService = $reservationControlService;
    }

    public function render()
    {
        $this->canDoReservation = true;

        if ($this->check_in_date && $this->check_out_date) {
            $this->totalPriceToPay = moneyFormat(($this->calculateTotalPrice() * 30) / 100);
            $this->totalPrice = moneyFormat($this->calculateTotalPrice());
        } else {
            $this->totalPriceToPay = moneyFormat(($this->room->price * 30) / 100);
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


    public function save()
    {
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

            if ($under18count > 1) {
                $this->addError('guests', '18 yaşından küçük misafir sayısı 3\'ten fazla olamaz.');
                return;
            }

            if ($above18count > 3) {
                $this->addError('guests', '18 yaşından büyük misafir sayısı 3\'ten fazla olamaz.');
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

        } catch (\Exception $exception) {
            $this->addError('error', 'Bir hata oluştu. Lütfen tekrar deneyiniz.');
            return redirect()
                ->back()
                ->withInput();
        }

        return redirect()
            ->route('user-reservation.myReservations')
            ->with('success', 'Rezervasyonunuz başarıyla oluşturuldu.');

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

    public function scriptUpdated()
    {
        $this->dispatchBrowserEvent('refresh-script');
    }
}
