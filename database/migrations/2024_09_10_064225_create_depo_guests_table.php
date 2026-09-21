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
        Schema::create('depo_guests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('depo_guest_name');
            $table->integer('depo_guest_rank')->nullable();
            $table->string('depo_guest_designation')->nullable();
            $table->string('depo_guest_contact')->nullable();
            $table->string('depo_guest_service')->nullable();
            $table->string('depo_identity')->nullable()->unique();
            $table->string('depo_guest_email')->nullable();
            $table->string('badge_type')->nullable();
            $table->uuid('depo_uid');
            $table->integer('isPrinted')->default(0);
            $table->timestamps();
            $table->string('depo_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depo_guests');
    }
};
