<?php

namespace App\Http\Controllers\admin;

use App\Enums\ReservationStatusEnum;
use App\Exports\PaymentExport;
use App\Exports\ReservationExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ReservationUpdateRequest;
use App\Jobs\SendOrderApprovedSmsJob;
use App\Models\AuthorizedHotel;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TransactionDetail;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RezervationManagementController extends Controller
{

    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }


    public function index(Request $request): View
    {

        if (auth()->user()->role == 'ADMIN') {

            $reservations = Reservation::query()
                ->with([
                    'room',
                    'room.hotel'
                ])
                ->orderBy('created_at', 'desc');

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
                ->orderBy('created_at', 'desc');

        }

        if ($request->has('statusKey') && $request->statusKey != 'all' && !empty($request->statusKey)) {
            $reservations->where('reservation_status', $request->statusKey);
        }

        if ($request->has('id') && !empty($request->id)) {
            $reservations->where('id', $request->id);
        }

        if ($request->has('searchKey') && !empty($request->searchKey)) {
            $searchKey = strtolower($request->input('searchKey'));


            $reservations->whereHas('user', function ($query) use ($searchKey) {
                $query->whereRaw('LOWER(name) like ?', ['%' . $searchKey . '%'])
                    ->orWhereRaw('LOWER(identity_number) like ?', ['%' . $searchKey . '%'])
                    ->orWhereRaw('LOWER(phone_number) like ?', ['%' . $searchKey . '%'])
                    ->orWhereRaw('LOWER(bank_transfer_code) like ?', ['%' . $searchKey . '%']);
            });
        }

        if ($request->has('checkIn') && !empty($request->checkIn) && empty($request->checkOut)) {
            $reservations->where('check_in_date', $request->checkIn);
        }

        if ($request->has('checkIn') && !empty($request->checkIn) && !empty($request->checkOut)) {
                $reservations->where(function ($q) use($request) {
                    $q->whereBetween('check_in_date', [$request->checkIn, $request->checkOut])
                        ->orWhereBetween('check_out_date', [$request->checkIn, $request->checkOut]);
            });
        }

        if (!empty($request->checkOut) && empty($request->checkIn)) {
            $reservations->where('check_out_date', $request->checkOut);
        }

        if ($request->has('roomId') && !empty($request->roomId)) {
            if ($request->roomId != 'all'){
                $reservations->where('room_id', $request->roomId);

            }
        }




        $reservations = $reservations->paginate(12);



        return view('admin.reservationManagement.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {

        $room = Room::query()
            ->findOrFail($reservation->room_id);

        $hotel = Hotel::query()
            ->findOrFail($room->hotel_id);


        if (auth()->user()->role != 'ADMIN') {
            $authroizedHotels = AuthorizedHotel::query()
                ->where('user_id', auth()->id())
                ->get();

            if (!$authroizedHotels->contains('hotel_id', $hotel->id)) {
                return redirect()
                    ->back()
                    ->with('error', 'Yetkisiz işlem.');
            }
        }

        return view('admin.reservationManagement.show', compact('reservation', 'room', 'hotel'));
    }

    public function update(ReservationUpdateRequest $request, Reservation $reservation)
    {


        if (auth()->user()->role != 'ADMIN') {
            $room = Room::query()
                ->findOrFail($reservation->room_id);

            $authroizedHotels = AuthorizedHotel::query()
                ->where('user_id', auth()->id())
                ->get();

            if (!$authroizedHotels->contains('hotel_id', $room->hotel->id)) {
                return redirect()
                    ->route('reservation.index')
                    ->with('error', 'Yetkisiz işlem.');
            }
        }

        // validate the request->paid_amount for money format without laravel validation
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $request->validated()['paid_amount'])) {
            return redirect()
                ->back()
                ->with('error', 'Ödenen tutar formatı hatalı.');
        }


        $reservation->update([
            'reservation_status' => $request->validated()['reservation_status'],
            'paid_amount' => $request->validated()['paid_amount'],
            'total_amount' => $request->validated()['total_amount']
        ]);


        if ($reservation->reservation_status == ReservationStatusEnum::Success->name) {
            SendOrderApprovedSmsJob::dispatch($this->smsService, $reservation->user);
        }

        return redirect()
            ->back()
            ->with('success', 'Rezervasyon güncellendi.');
    }


    public function exportExcel(Request $request){

        if (auth()->user()->role == 'ADMIN') {

            $reservations = Reservation::query()
                ->with([
                    'room',
                    'room.hotel'
                ])
                ->orderBy('created_at', 'desc');

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
                ->orderBy('created_at', 'desc');

        }

        if ($request->has('statusKey') && $request->statusKey != 'all' && !empty($request->statusKey)) {
            $reservations->where('reservation_status', $request->statusKey);
        }

        if ($request->has('id') && !empty($request->id)) {
            $reservations->where('id', $request->id);
        }

        if ($request->has('searchKey') && !empty($request->searchKey)) {
            $searchKey = strtolower($request->input('searchKey'));


            $reservations->whereHas('user', function ($query) use ($searchKey) {
                $query->whereRaw('LOWER(name) like ?', ['%' . $searchKey . '%'])
                    ->orWhereRaw('LOWER(identity_number) like ?', ['%' . $searchKey . '%'])
                    ->orWhereRaw('LOWER(phone_number) like ?', ['%' . $searchKey . '%'])
                    ->orWhereRaw('LOWER(bank_transfer_code) like ?', ['%' . $searchKey . '%']);
            });
        }

        if ($request->has('checkIn') && !empty($request->checkIn) && empty($request->checkOut)) {
            $reservations->where('check_in_date', $request->checkIn);
        }

        if ($request->has('checkIn') && !empty($request->checkIn) && !empty($request->checkOut)) {
            $reservations->where(function ($q) use($request) {
                $q->whereBetween('check_in_date', [$request->checkIn, $request->checkOut])
                    ->orWhereBetween('check_out_date', [$request->checkIn, $request->checkOut]);
            });
        }

        if (!empty($request->checkOut) && empty($request->checkIn)) {
            $reservations->where('check_out_date', $request->checkOut);
        }

        if ($request->has('roomId') && !empty($request->roomId)) {
            if ($request->roomId != 'all'){
                $reservations->where('room_id', $request->roomId);

            }
        }




        $reservations = $reservations->get();


        return Excel::download(new ReservationExport($reservations), 'reservation.xlsx');
    }

    public function exportExcelPayment(Request $request){

        $details = TransactionDetail::query()->with('reservation')->whereHas('reservation')
            ->whereHas('reservation.user');

        if ($request->has('statusKey') && $request->statusKey != 'all' && !empty($request->statusKey)) {
            $details->where('status', $request->statusKey);
        }

        if ($request->has('id') && !empty($request->id)) {
            $details->whereHas('reservation', function ($q) use($request) {
                $q->where('id', $request->id);
            });
        }

        if ($request->has('searchKey') && !empty($request->searchKey)) {
            $searchKey = $request->input('searchKey');

            $details->where(function ($query) use ($searchKey) {
                $query->whereHas('reservation.user', function ($q) use ($searchKey) {
                    $q->whereRaw('LOWER(name) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(identity_number) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(phone_number) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(bank_transfer_code) like ?', ['%' . $searchKey . '%']);
                });
            });
        }

        $details->orderBy('created_at', 'desc');


        $details = $details->get();



        return Excel::download(new PaymentExport($details), 'payment.xlsx');

    }

    public function groupByList(Request $request){

        $checkIn = $request->input('checkIn');
        $checkOut = $request->input('checkOut');

        // Rezervasyonları tarihlerine göre gruplamak için sorguyu oluşturun
        $reservations = Reservation::query()
            ->select('check_in_date', 'check_out_date', DB::raw('COUNT(*) as reservation_count'))
            ->groupBy('check_in_date', 'check_out_date')
            ->get();

        return view('admin.reservationManagement.groupByList', compact('reservations'));

    }


}
