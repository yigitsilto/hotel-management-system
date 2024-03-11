<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class ReservationExport implements FromView, WithColumnWidths
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


    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'J' => 15,
            'K' => 60,
        ];
    }
}
