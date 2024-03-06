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


        $details = TransactionDetail::query()->with('reservation');

        if ($request->has('statusKey') && $request->statusKey != 'all') {
            $details->where('status', $request->statusKey);
        }

        if ($request->has('searchKey')) {
            $searchKey = $request->input('searchKey');

            $details->where(function ($query) use ($searchKey) {
                $query->whereHas('reservation.user', function ($q) use ($searchKey) {
                    $q->whereRaw('LOWER(name) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(identity_number) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(phone_number) like ?', ['%' . $searchKey . '%'])
                        ->orWhereRaw('LOWER(bank_transfer_code) like ?', ['%' . $searchKey . '%']);
                });

                if (!is_numeric($searchKey)) {
                    $searchKey = strtolower($searchKey);
                }

                $query->orWhereHas('reservation', function ($q) use ($searchKey) {
                    $q->where('id', $searchKey);
                });
            });
        }

        $details->orderBy('created_at', 'desc');


        $details = $details->paginate(12);




        return view('admin.transactionDetail.index', compact('details'));
    }


}
