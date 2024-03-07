<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ReservationExport implements FromView
{


    public function view(): View
    {
        return view('admin.reservationManagement.export', [
            'reservations' => Reservation::query()->with(['room', 'user'])->get()
        ]);
    }
}
