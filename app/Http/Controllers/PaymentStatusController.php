<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function success(Request $request)
    {
        $oid = isset($request->oid) ? $request->oid : $request->ReturnOid;

        $reservation = \App\Models\Reservation::query()->where('id', $oid)->first();

        if ($reservation){
            $reservation->paid_amount = $request->amount;
            $reservation->status = \App\Enums\ReservationStatusEnum::Success->name;
            $reservation->save();
        }


        dd($request->all());

        return view('user.success-payment');



    }

    public function failed(Request $request)
    {
        $oid = isset($request->oid) ? $request->oid : $request->ReturnOid;
        $reservation = \App\Models\Reservation::query()->where('id', $oid)->first();

        $reservation?->delete();

        dd($request->all());


        return view('user.fail-payment');

    }
}
