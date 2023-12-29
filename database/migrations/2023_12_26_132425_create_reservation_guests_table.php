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
        Schema::create('reservation_guests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->string('name');
            $table->integer('age');
            $table->boolean('is_under_18')->default(false);
            $table->timestamps();
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_guests');
    }
};
