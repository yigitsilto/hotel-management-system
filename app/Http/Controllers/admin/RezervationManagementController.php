<?php

namespace App\Http\Controllers\admin;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Jobs\SendOrderApprovedSmsJob;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RezervationManagementController extends Controller
{

    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }


    public function index(): View
    {
        $reservations = Reservation::query()
                                   ->with([
                                              'room',
                                              'room.hotel'
                                          ])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('admin.reservationManagement.index', compact('reservations'));
    }

    public function show(Reservation $reservation): View
    {

        $room = Room::query()
                    ->findOrFail($reservation->room_id);

        $hotel = Hotel::query()
                      ->findOrFail($room->hotel_id);
        return view('admin.reservationManagement.show', compact('reservation', 'room', 'hotel'));
    }

    public function update(Request $request, Reservation $reservation): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
                               'reservation_status' => 'required',
                               'paid_amount' => 'required|numeric',
                           ]);
        $reservation->update([
                                 'reservation_status' => $request->reservation_status,
                                 'paid_amount' => $request->paid_amount,
                             ]);


        if ($reservation->reservation_status == ReservationStatusEnum::Success->name) {
            SendOrderApprovedSmsJob::dispatch($this->smsService, $reservation->user);
        }

        return redirect()
            ->back()
            ->with('success', 'Rezervasyon güncellendi.');
    }


}
