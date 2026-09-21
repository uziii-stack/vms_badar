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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Polymorphic relation (this replaces visitor_id, depo_guest_id, etc.)
            $table->morphs('attendee'); // attendee_id + attendee_type
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->date('attendance_date')->nullable();
            $table->boolean('present')->default(true);
            $table->timestamp('checked_in_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
