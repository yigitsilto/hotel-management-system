<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
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
        return view('user.reservationShow', compact( 'room', 'hotel'));
    }

    public function showRoom(Room $room): View
    {
        $hotel = $room->hotel;
        return view('user.room-detail', compact( 'room', 'hotel'));
    }


}
