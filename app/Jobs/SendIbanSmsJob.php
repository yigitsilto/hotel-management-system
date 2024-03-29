<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendIbanSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private SmsService $smsService;
    private User $user;

    private $code;

    public function __construct(SmsService $smsService, User $user, $code)
    {
        $this->smsService = $smsService;
        $this->user = $user;
        $this->code = $code;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->smsService->sendBankInformationSms($this->user, $this->code);
    }
}
