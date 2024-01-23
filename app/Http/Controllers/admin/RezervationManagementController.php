<?php

namespace App\Http\Controllers\admin;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Jobs\SendOrderApprovedSmsJob;
use App\Models\AuthorizedHotel;
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

        if (auth()->user()->role == 'ADMIN') {

            $reservations = Reservation::query()
                                       ->with([
                                                  'room',
                                                  'room.hotel'
                                              ])
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(10);

        } else {
            $authroizedHotels = AuthorizedHotel::query()
                                               ->where('user_id', auth()->id())
                                               ->get();


            $reservations = Reservation::query()
                                       ->with([
                                                  'room',
                                                  'room.hotel' => function ($query) use ($authroizedHotels) {
                                                      $query->whereIn('id', $authroizedHotels->pluck('hotel_id'));
                                                  },
                                              ])
                                       ->whereHas('room.hotel', function ($query) use ($authroizedHotels) {
                                           $query->whereIn('id', $authroizedHotels->pluck('hotel_id'));
                                       })
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(10);

        }
        return view('admin.reservationManagement.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {

        $room = Room::query()
                    ->findOrFail($reservation->room_id);

        $hotel = Hotel::query()
                      ->findOrFail($room->hotel_id);


        if (auth()->user()->role != 'ADMIN'){
            $authroizedHotels = AuthorizedHotel::query()
                                               ->where('user_id', auth()->id())
                                               ->get();

            if (!$authroizedHotels->contains('hotel_id', $hotel->id)){
                return redirect()
                    ->back()
                    ->with('error', 'Yetkisiz işlem.');
            }
        }

        return view('admin.reservationManagement.show', compact('reservation', 'room', 'hotel'));
    }

    public function update(Request $request, Reservation $reservation): \Illuminate\Http\RedirectResponse
    {


        if (auth()->user()->role != 'ADMIN'){
            $room = Room::query()
                          ->findOrFail($reservation->room_id);

            $authroizedHotels = AuthorizedHotel::query()
                                               ->where('user_id', auth()->id())
                                               ->get();

            if (!$authroizedHotels->contains('hotel_id', $room->hotel->id)){
                return redirect()
                    ->route('reservation.index')
                    ->with('error', 'Yetkisiz işlem.');
            }
        }


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
