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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->string('title');
            $table->string('base_image');
            $table->enum('room_type', \App\Enums\RoomTypeEnum::getStringValue());
            $table->integer('capacity');
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_available')->default(true); // Otel müsait mi?
            $table->integer('same_room_count')->default(1); // Aynı odadan kaç tane var?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
