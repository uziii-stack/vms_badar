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
        Schema::create('staff_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('government_staff')->onUpdate('no action')->onDelete('restrict');
            $table->foreignId('program_id')->constrained('programs')->onUpdate('no action')->onDelete('restrict');
            $table->unique(['staff_id', 'program_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_program');
    }
};
