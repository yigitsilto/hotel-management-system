<?php

namespace App\Livewire;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class ReservationCreatePage extends Component
{
    public $totalPriceToPay;
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
    ];


    public function render()
    {
        if ($this->check_in_date && $this->check_out_date) {
            $this->totalPriceToPay = moneyFormat($this->calculateTotalPrice());
        } else {
            $this->totalPriceToPay = moneyFormat($this->room->price);
        }
        return view('livewire.reservation-create-page', [
            'room' => $this->room,
            // Pass the room information to the Livewire view
        ]);
    }

    public function calculateTotalPrice()
    {
        $checkInDate = Carbon::parse($this->check_in_date);
        $checkOutDate = Carbon::parse($this->check_out_date);
        $totalDayCount = $checkInDate->diffInDays($checkOutDate) == 0 ? 1 : $checkInDate->diffInDays($checkOutDate);
        return $this->room->price * $totalDayCount;
    }

    //TODO kontroller eklenecek oda tükendi mi vs gibi
    public function save()
    {
        $this->validate();
        try {
            if ($this->reservationDuplicateCheck()) {
                return;
            }

            $this->createReservation()
                 ->guests()
                 ->createMany($this->guests);;

        } catch (\Exception $exception) {
            $this->addError('error', 'Bir hata oluştu. Lütfen tekrar deneyiniz.');
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
                                       'total_amount' => $this->room->price * $totalDayCount,
                                       'paid_amount' => $paidAmount,
                                       'transaction_id' => Str::uuid(),
                                   ]);

    }


    /**
     * private function createReservation(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * {
     * $checkInDate = Carbon::parse($this->check_in_date);
     * $checkOutDate = Carbon::parse($this->check_out_date);
     * $totalDayCount = $checkInDate->diffInDays($checkOutDate);
     *
     * // Kontrol 1: 4 ay için yapılacak tatillerde 2 yılda bir 4 gün hakkı
     * $twoYearsAgo = Carbon::now()->subYears(2);
     * $reservationCountWithinTwoYears = Reservation::where('user_id', auth()->id())
     * ->whereBetween('created_at', [$twoYearsAgo, Carbon::now()])
     * ->count();
     *
     * if ($reservationCountWithinTwoYears % 2 === 0 && $totalDayCount <= 4) {
     * // Kontrol 2: 4 günden az yapabilir
     * $remainingDays = $this->getRemainingDays(auth()->id());
     *
     * if ($totalDayCount <= $remainingDays) {
     * // Rezervasyon oluştur
     * return Reservation::query()
     * ->create([
     * 'room_id' => $this->room->id,
     * 'user_id' => auth()->id(),
     * 'number_of_guests' => $this->guestSize,
     * 'check_in_date' => $this->check_in_date,
     * 'check_out_date' => $this->check_out_date,
     * 'special_requests' => $this->special_requests,
     * 'payment_method' => $this->payment_method,
     * 'reservation_status' => ReservationStatusEnum::Pending->name,
     * 'total_amount' => $this->room->price * $totalDayCount,
     * 'paid_amount' => 0,
     * 'transaction_id' => Str::uuid(),
     * ]);
     * } else {
     * // Hata: Kullanılabilir gün sayısı aşıldı
     * // Uygun bir hata yönetimi ekleyebilirsiniz
     * return null;
     * }
     * } else {
     * // Hata: Koşullar sağlanmıyor
     * // Uygun bir hata yönetimi ekleyebilirsiniz
     * return null;
     * }
     * }
     *
     * private function getRemainingDays($userId): int
     * {
     * // Kontrol 3: 2 yıl geçtikten sonra kalan 4 ayı tekrar kullanabilir
     * $twoYearsAgo = Carbon::now()->subYears(2);
     * $reservationsAfterTwoYears = Reservation::where('user_id', $userId)
     * ->where('created_at', '>', $twoYearsAgo)
     * ->get();
     *
     * $usedDays = 0;
     * foreach ($reservationsAfterTwoYears as $reservation) {
     * $usedDays += Carbon::parse($reservation->check_in_date)
     * ->diffInDays(Carbon::parse($reservation->check_out_date));
     * }
     *
     * $remainingDays = 8 - $usedDays;
     * return max(0, $remainingDays);
     * }
     *
     */

}
