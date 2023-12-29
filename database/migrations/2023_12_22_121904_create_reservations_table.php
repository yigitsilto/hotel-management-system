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
            $table->dateTime('check_in_date');
            $table->dateTime('check_out_date');
            $table->integer('number_of_guests'); // Toplam konuk sayısı
            $table->text('special_requests')->nullable(); // Özel talepler ve notlar
            $table->string('reservation_status'); // Rezervasyon durumu
            $table->decimal('total_amount', 10, 2); // Total amount paid for the reservation
            $table->decimal('paid_amount', 10, 2); // Amount paid for the reservation (e.g., deposit)
            $table->string('payment_method'); // Payment method (e.g., credit_card, bank_transfer, etc.)
            $table->string('transaction_id')->nullable(); // Unique identifier for the payment transaction
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
