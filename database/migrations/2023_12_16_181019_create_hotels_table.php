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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('total_rooms')->default(0); // Toplam oda sayısı
            $table->string('contact_email')->nullable(); // Otel ile iletişim için e-posta
            $table->string('contact_phone')->nullable(); // Otel ile iletişim için telefon
            $table->boolean('is_available')->default(true); // Otel müsait mi?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
