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
        Schema::create('visitors1', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('attandeeCountry')->nullable();
            $table->string('attandeeCompany')->nullable();
            $table->date('dob')->nullable();
            $table->string('identity')->unique();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('code')->unique();
            $table->integer('day_1')->default(0);
            $table->integer('day_2')->default(0);
            $table->integer('day_3')->default(0);
            $table->integer('day_4')->default(0);
            $table->integer('seminar')->default(0);
            $table->string('badge_print')->default('0');
            $table->string('dupe_badge_print')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors1');
    }
};
