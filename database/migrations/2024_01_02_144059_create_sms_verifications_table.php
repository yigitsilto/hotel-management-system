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
        Schema::create('sms_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('phone_number');
            $table->string('code');
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('sms_verified_at')->nullable()->after('email_verified_at');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_verifications');
    }
};
