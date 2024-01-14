<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function success(Request $request)
    {
        $oid = isset($request->oid) ? $request->oid : $request->ReturnOid;

       $oidValue = explode('-', $oid)[0];

        $reservation = \App\Models\Reservation::query()->withoutGlobalScope('payment_status')->where('id', $oidValue)->first();


        if (!empty($reservation)){
            $reservation->paid_amount = $request->amount;
            $reservation->reservation_status = \App\Enums\ReservationStatusEnum::Success->name;
            $reservation->payment_status = true;
            $reservation->save();
        }

        return view('user.success-payment');

    }

    public function failed(Request $request)
    {
        $oid = isset($request->oid) ? $request->oid : $request->ReturnOid;

        $oidValue = explode('-', $oid)[0];

        $reservation = \App\Models\Reservation::query()->withoutGlobalScope('payment_status')->where('id', $oidValue)->first();

        if (!empty($reservation)){
            $reservation?->delete();
        }

        dd($request->all());


        return view('user.fail-payment');

    }
}
