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


        $details = $details->paginate(12);




        return view('admin.transactionDetail.index', compact('details'));
    }


}
