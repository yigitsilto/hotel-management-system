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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('number_of_guests'); // Toplam konuk sayısı
            $table->boolean('is_under_18'); // 18 yaş altında mı?
            $table->text('special_requests')->nullable(); // Özel talepler ve notlar
            $table->string('reservation_status'); // Rezervasyon durumu
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
