<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BankTransferCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bank-transfer-check-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $test = new \App\Services\BankTransferCheckService();
        $test->check();
    }
}
