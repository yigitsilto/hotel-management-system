<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $details = TransactionDetail::query()->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.transactionDetail.index', compact('details'));
    }


}
