<?php

namespace App\Services;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class ReservationControlService
{
    public array $errors = [];

    public function isRoomAvailable($room, $checkInDate, $checkOutDate, $guests = null, $userId = null): array
    {
       $userId = $userId != null ? $userId : auth()->id();
        // oda rezervasyon sayısı kontrolü
        $reservationCountCheck = $this->checkReservationCountAvailability($room, $checkInDate, $checkOutDate);

        // max 4 gün rezervasyon yapılabilir
        $maxDayCountCheck = $this->checkMaxDayCount($room, $checkInDate, $checkOutDate);

        $userReservations = Reservation::query()
                                       ->where('user_id', $userId)
                                       ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                                       ->orderBy('check_out_date', 'desc')
                                       ->first();

        $monthsAvailabilityCheck = $this->checkMonthsAvailability($room, $checkInDate, $checkOutDate,
                                                                  $userReservations);


        // parent kontrolleri akraba ilişkilerinde de yapılmış ise yine aynı yaz ayları kontrolü olmalı
        $parentCheck = $this->checkMontAvailabilityForRelationalUser($room, $checkInDate, $checkOutDate, $userId);

        $guestCheck = $this->checkGuestsMonthAvailability($room, $checkInDate, $checkOutDate, $guests);

        $check = $reservationCountCheck && $monthsAvailabilityCheck && $maxDayCountCheck && $parentCheck && $guestCheck;

        return [
            'status' => $check,
            'errors' => $this->errors
        ];
    }
    public function checkGuestsMonthAvailability($room, $checkInDate, $checkOutDate, $guests) {

        foreach ($guests as $guest) {
            $tc = $guest['tc'];
            $user = User::query()->where('identity_number', $tc)->first();
            if ($user) {

                $userReservations = Reservation::query()
                    ->where('user_id', $user->id)
                    ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                    ->orderBy('check_out_date', 'desc')
                    ->first();

                $monthsAvailabilityCheck = $this->checkMonthsAvailability($room, $checkInDate, $checkOutDate,
                    $userReservations);

                if (!$monthsAvailabilityCheck) {
                    return false;
                }

                $parentUserReservations =  Reservation::query()
                    ->where('user_id', $user->parent_id)
                    ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                    ->orderBy('check_out_date', 'desc')
                    ->first();

                $parentCheck = $this->checkMonthsAvailability($room, $checkInDate, $checkOutDate,
                    $parentUserReservations);

                if (!$parentCheck) {
                    return false;
                }


            }
        }


        return true;
    }

    public function checkMontAvailabilityForRelationalUser($room, $checkInDate, $checkOutDate, $userId){
        $parentCheck = true;
        $user = User::query()->where('id', $userId)->first();

        if ($user->parent_id != null) {
            $parentUserReservations =  Reservation::query()
                ->where('user_id', $user->parent_id)
                ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                ->orderBy('check_out_date', 'desc')
                ->first();

            $parentCheck = $this->checkMonthsAvailability($room, $checkInDate, $checkOutDate,
                $parentUserReservations);

        }

        return $parentCheck;
    }

    private function checkReservationCountAvailability($room, $checkInDate, $checkOutDate): bool
    {
        $reservations = Reservation::query()
                                   ->where('room_id', $room->id)
                                   ->where(function ($query) use ($checkInDate, $checkOutDate) {
                                       $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                                           $q->where('check_in_date', '<', $checkOutDate)
                                             ->where('check_out_date', '>', $checkInDate);
                                       })->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                                           $q->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                                             ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate]);
                                       });
                                   })
                                   ->where('reservation_status', '!=', ReservationStatusEnum::Rejected->name)
                                   ->where('payment_status', true)
                                   ->count();

        $check = $room->same_room_count > $reservations;

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
            $this->errors[] = 'Seçtiğiniz oda türü için daha önce sizin veya 1. ilişkili akrabanızın rezervasyonu bulunduğu için ' . $blockedYearInterval . ' yıl içerisinde yaz aylarında rezervasyon yapamazsınız.';
            return false; // Kullanıcı engellendiği için rezervasyon yapılamaz
        }


        return true; // Kullanıcı engelli değil, rezervasyon yapılabilir
    }


}
