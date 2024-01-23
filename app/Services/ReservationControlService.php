<?php

namespace App\Services;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationControlService
{
    public array $errors = [];

    public function isRoomAvailable($room, $checkInDate, $checkOutDate): array
    {
        // oda rezervasyon sayısı kontrolü
        $reservationCountCheck = $this->checkReservationCountAvailability($room, $checkInDate);

        // max 4 gün rezervasyon yapılabilir
        $maxDayCountCheck = $this->checkMaxDayCount($room, $checkInDate, $checkOutDate);

        $userReservations = Reservation::query()
                                       ->where('user_id', auth()->id())
                                       ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                                       ->orderBy('check_out_date', 'desc')
                                       ->first();

        $monthsAvailabilityCheck = $this->checkMonthsAvailability($room, $checkInDate, $checkOutDate,
                                                                  $userReservations);

        $check = $reservationCountCheck && $monthsAvailabilityCheck && $maxDayCountCheck;

        return [
            'status' => $check,
            'errors' => $this->errors
        ];
    }

    private function checkReservationCountAvailability($room, $checkInDate): bool
    {
        $reservations = Reservation::query()
                                   ->where('room_id', $room->id)
                                   ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                                   ->where('check_out_date', '>=', $checkInDate)
                                   ->where('payment_status', true)
                                   ->count();


        dd($room->same_room_count, $reservations);
        $check = ($room->same_room_count - 1) >= $reservations;

        if (!$check) {
            $this->errors[] = 'Seçtiğiniz oda türü seçilen tarihler arasında müsaitlik bulunmamaktadır.';
        }

        return $check;

    }

    private function checkMaxDayCount($room, $checkInDate, $checkOutDate): bool
    {

        $maxDayCount = $room->hotel->max_stayed_count;

        if ($maxDayCount == 0) {
            return true;
        }
        $stayDuration = Carbon::parse($checkOutDate)
                              ->diffInDays($checkInDate);

        $check = $stayDuration <= $maxDayCount;

        if (!$check) {
            $this->errors[] = 'Seçtiğiniz oda türü için maksimum ' . $maxDayCount . ' gece rezervasyon yapabilirsiniz.';
        }

        return $check;
    }

    private function checkMonthsAvailability($room, $checkInDate, $checkOutDate, $userReservations): bool
    {
        $hotel = $room->hotel;
        $hotelReservationMonths = $hotel->reservationMonths()
                                        ->pluck('value')
                                        ->toArray();

        $arrivalDate = Carbon::parse($checkInDate);
        $selectedMonth = $arrivalDate->format('m');

        if (!in_array($selectedMonth, $hotelReservationMonths)) {
            return $this->checkBlockedYear($room, $userReservations);
        }

        $dateForCheckout = Carbon::parse($checkOutDate);
        $selectedMonthForCheckout = $dateForCheckout->format('m');

        if (!in_array($selectedMonthForCheckout, $hotelReservationMonths)) {
            return $this->checkBlockedYear($room, $userReservations);
        }

        return true;
    }

    private function checkBlockedYear($room, $userReservations): bool
    {
        $blockedYearInterval = $room->hotel->blocked_year; // engelleme süresi


        if (!$userReservations) {
            return true; // Kullanıcı engelli değil, rezervasyon yapılabilir
        }

        if ($blockedYearInterval == 0) {
            return true; // Kullanıcı engelli değil, rezervasyon yapılabilir
        }

        $blockedUntil = Carbon::parse($userReservations->check_in_date)
                              ->addYears($blockedYearInterval);


        if (now()->lessThan($blockedUntil)) {
            $this->errors[] = 'Seçtiğiniz oda türü için daha önce rezervasyonunuz bulunduğu için ' . $blockedYearInterval . ' yıl içerisinde yaz aylarında rezervasyon yapamazsınız.';
            return false; // Kullanıcı engellendiği için rezervasyon yapılamaz
        }


        return true; // Kullanıcı engelli değil, rezervasyon yapılabilir
    }


}