<?php

namespace App\Http\Controllers\user;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\View\View;

class RezervationController extends Controller
{
    public function index(Hotel $hotel): View
    {
        $rooms = $hotel->rooms()
                       ->where('is_available', 1)
                       ->get();

        return view('user.rezervation', compact('hotel', 'rooms'));
    }

    public function show(Room $room): View
    {
        $hotel = $room->hotel;
        return view('user.reservationShow', compact('room', 'hotel'));
    }

    public function showRoom(Room $room): View
    {
        $hotel = $room->hotel;
        return view('user.room-detail', compact('room', 'hotel'));
    }

    public function createReservation(Room $room): View
    {
        $hotel = $room->hotel;

        return view('user.reservation-create', compact('room', 'hotel'));
    }

    public function myReservations(): View
    {
        $reservations = Reservation::query()
                                   ->with([
                                              'room',
                                              'room.hotel'
                                          ])
                                   ->where('user_id', auth()->id())
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('user.my-reservations', compact('reservations'));
    }

    public function myReservationDetail(Reservation $reservation): View
    {
        $room = Room::query()
                    ->findOrFail($reservation->room_id);

        $hotel = Hotel::query()
                      ->findOrFail($room->hotel_id);
        return view('user.my-reservation-detail', compact('reservation', 'room', 'hotel'));
    }

    public function requestCancelReservation(Reservation $reservation)
    {

        if ($reservation->reservation_status == ReservationStatusEnum::Pending->name && $reservation->paid_amount < 1) {

            $reservation->update([
                                     'reservation_status' => ReservationStatusEnum::CanselledByUser->name
                                 ]);
            return redirect()
                ->back()
                ->with('success', 'Rezervasyon iptal edildi.');
        }

        if ($reservation->reservation_status == ReservationStatusEnum::CanselledByUser->name) {
            return redirect()
                ->back()
                ->with('info', 'Rezervasyon zaten iptal edilmiş.');
        }
        return redirect()
            ->back()
            ->with('error', 'Rezervasyon iptali için lütfen otelden yardım isteyiniz.');

    }


}
