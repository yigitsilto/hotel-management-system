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
        \App\Models\Setting::query()->create([
            'key' => 'user_approved_sms',
            'value' => 'Kullanıcı bilginiz onaylanmıştır. İyi günler dileriz.'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
