<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReservationExport implements FromView
{

    private $query;
    public function __construct($values)
    {
        $this->query = $values;
    }

    public function view(): View
    {
        return view('admin.reservationManagement.export', [
            'reservations' => $this->query
        ]);
    }
}
