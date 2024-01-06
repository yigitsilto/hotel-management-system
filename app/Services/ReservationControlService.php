<?php

namespace App\Services;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationControlService
{


    public function isRoomAvailable($room, $checkInDate, $checkOutDate): bool
    {
        $reservationCountCheck = $this->checkReservationCountAvailability($room, $checkInDate);
        $monthsAvailabilityCheck = $this->checkMonthsAvailability($room, $checkInDate);
        $maxDayCountCheck = $this->checkMaxDayCount($room, $checkInDate, $checkOutDate);
        $blockedYearCheck = $this->checkBlockedYear($room, $checkInDate, $checkOutDate);

        return $reservationCountCheck && $monthsAvailabilityCheck && $maxDayCountCheck && $blockedYearCheck;
    }

    private function checkReservationCountAvailability($room, $checkInDate): bool
    {
        // check out date i geçmemiş rezervasyon sayısı
        return $room->same_room_count > $room->reservations()
                                             ->where('check_out_date', '>', $checkInDate)
                                             ->count();
    }

    private function checkMonthsAvailability($room, $checkInDate): bool
    {
        $hotel = $room->hotel;
        $hotelReservationMonths = $hotel->reservationMonths()
                                        ->pluck('value')
                                        ->toArray();
        $arrivalDate = Carbon::parse($checkInDate);
        $selectedMonth = $arrivalDate->format('m');
        return in_array($selectedMonth, $hotelReservationMonths);

    }


    private function checkMaxDayCount($room, $checkInDate, $checkOutDate): bool
    {

        $maxDayCount = $room->hotel->max_stayed_count; // Buraya max_day_count değerini ekleyebilirsiniz
        $stayDuration = Carbon::parse($checkOutDate)
                              ->diffInDays($checkInDate);

        return $stayDuration <= $maxDayCount;
    }

    private function checkBlockedYear($room, $checkInDate, $checkOutDate): bool
    {
        $blockedYearInterval = $room->hotel->blocked_year; // engelleme süresi

        $userReservations = Reservation::query()
                                       ->where('user_id', auth()->id())
                                       ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                                       ->orderBy('check_out_date', 'desc')
                                       ->first();


        if (!$userReservations) {
            return true; // Kullanıcı engelli değil, rezervasyon yapılabilir
        }

        if ($blockedYearInterval == 0) {
            return true; // Kullanıcı engelli değil, rezervasyon yapılabilir
        }

        $blockedUntil = Carbon::parse($userReservations->check_in_date)
                              ->addYears($blockedYearInterval);


        if (now()->lessThan($blockedUntil)) {
            return false; // Kullanıcı engellendiği için rezervasyon yapılamaz
        }


        return true; // Kullanıcı engelli değil, rezervasyon yapılabilir
    }


}