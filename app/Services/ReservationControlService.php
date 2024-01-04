<?php

namespace App\Services;

class ReservationControlService
{

    public function isRoomAvailable($room): bool {
        return $this->checkReservationCountAvailability($room);
    }

    private function checkReservationCountAvailability($room): bool
    {
        return $room->same_room_count > $room->reservations()->where('check_out_date', '>', now())->count();
    }



}