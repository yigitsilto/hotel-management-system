<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendUserActiveSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private SmsService $smsService;
    private User $user;

    public function __construct(SmsService $smsService, User $user)
    {
        $this->smsService = $smsService;
        $this->user = $user;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->smsService->sendUserApprovedSms($this->user);
    }
}
