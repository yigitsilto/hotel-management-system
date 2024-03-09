<?php

namespace App\Exports;

use App\Models\Reservation;
use App\Models\TransactionDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentExport implements FromView
{

    private $query;
    public function __construct($values)
    {
        $this->query = $values;
    }
    public function view(): View
    {
        return view('admin.transactionDetail.export', [
            'details' => $this->query
        ]);
    }
}
