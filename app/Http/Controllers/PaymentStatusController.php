<?php

namespace App\Http\Controllers;

use App\Jobs\SendPaymentSuccessSmsJob;
use App\Models\TransactionDetail;
use App\Services\SmsService;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{

    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }


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


            $mustPaidAmount = ($reservation->total_amount * 30) / 100;

            $transactionDetail = new TransactionDetail();
            $transactionDetail->payment_method = 'credit_card';
            $transactionDetail->status = true;
            $transactionDetail->reservation_id = $reservation->id;
            $transactionDetail->paid_amount = $mustPaidAmount ;
            $transactionDetail->save();
        }


        $user = $reservation->user;

        $sendSms = SendPaymentSuccessSmsJob::dispatch($this->smsService, $user);


        return view('user.success-payment');

    }

    public function failed(Request $request)
    {
        $oid = isset($request->oid) ? $request->oid : $request->ReturnOid;

        $oidValue = explode('-', $oid)[0];

        $reservation = \App\Models\Reservation::query()->withoutGlobalScope('payment_status')->where('id', $oidValue)->first();

        if (!empty($reservation)){

            $transactionDetail = new TransactionDetail();
            $transactionDetail->payment_method = 'credit_card';
            $transactionDetail->status = false;
            $transactionDetail->reservation_id = $reservation->id;
            $transactionDetail->paid_amount = 0 ;
            $transactionDetail->error_reason = isset($request->ErrMsg) ?  $request->ErrMsg : 'Ödeme hatası';
            $transactionDetail->save();

            $reservation?->delete();


        }

        $errMsg = $request->ErrMsg;



        return view('user.fail-payment', compact('errMsg'));

    }
}
