<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $details = TransactionDetail::query()->with('reservation')->whereHas('reservation')
            ->orderBy('created_at', 'desc');

        if ($request->has('statusKey') && $request->statusKey != 'all') {
            $details->where('status', $request->statusKey);
        }

        if ($request->has('searchKey')) {
            $searchKey = $request->input('searchKey');

            $details->where(function ($query) use ($searchKey) {
                $query->whereHas('reservation.user', function ($subQuery) use ($searchKey) {
                    $subQuery->whereRaw('LOWER(name) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(identity_number) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(phone_number) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(bank_transfer_code) like ?', ['%' . $searchKey . '%']);
                });

                if (!is_numeric($searchKey)) {
                    $query->orWhereHas('reservation', function ($subQuery) use ($searchKey) {
                        $subQuery->where('id', $searchKey);
                    });
                } else {
                    $query->orWhere('id', $searchKey);
                }
            });
        }

        $results = $details->paginate(10);




        return view('admin.transactionDetail.index', compact('details'));
    }


}
