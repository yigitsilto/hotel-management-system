<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });

        // add dummy data with these keys reservation_approved_sms, payment_success_sms, login_sms , iban_sms

        \App\Models\Setting::query()->create([
            'key' => 'reservation_approved_sms',
            'value' => 'Rezervasyonunuz onaylanmıştır. İyi günler dileriz.'
        ]);

        \App\Models\Setting::query()->create([
            'key' => 'payment_success_sms',
            'value' => 'Ödemeniz başarıyla alınmıştır. İyi günler dileriz.'
        ]);

        \App\Models\Setting::query()->create([
            'key' => 'login_sms',
            'value' => 'Giriş yapmak için sms kodunuz : '
        ]);

        \App\Models\Setting::query()->create([
            'key' => 'iban_sms',
            'value' => 'IBAN numarası bilgisi TR123123123  '
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
