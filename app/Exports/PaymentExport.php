<?php

namespace App\Exports;

use App\Models\Reservation;
use App\Models\TransactionDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentExport implements FromView
{


    public function view(): View
    {
        return view('admin.transactionDetail.export', [
            'details' => TransactionDetail::query()
                ->with(['reservation', 'reservation.user'])
                ->whereHas('reservation')
                ->get()
        ]);
    }
}
