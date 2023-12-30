<?php

namespace App\Http\Controllers\admin;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hotels = Hotel::query()
                       ->orderBy('updated_at', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->limit(3)
                       ->get();

        $reservations = Reservation::query()
                                   ->with([
                                              'room',
                                              'user',
                                              'room.hotel'
                                          ])
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();

        $totalAmountFromReservations = Reservation::query()
                                                  ->sum('total_amount');

        $paidAmountFromReservations = Reservation::query()
                                                 ->sum('paid_amount');

        $reservationCount = Reservation::query()
                                       ->count();

        $waitingReservationCount = Reservation::query()
                                                 ->where('reservation_status', ReservationStatusEnum::Pending->name)
                                                 ->count();

        $values = [
            'hotels' => $hotels,
            'reservations' => $reservations,
            'totalAmountFromReservations' => $totalAmountFromReservations,
            'paidAmountFromReservations' => $paidAmountFromReservations,
            'reservationCount' => $reservationCount,
            'waitingReservationCount' => $waitingReservationCount,
        ];


        return view('admin.dashboard.index', compact('values'));
    }
}
