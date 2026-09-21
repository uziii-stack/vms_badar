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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('picture')->nullable();
            $table->string('sponsor_picture')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('event_time')->nullable();
            $table->string('event_location')->nullable();
            $table->string('website')->nullable();
            $table->integer('status')->default(1);
            $table->integer('deleted')->default(0);
            $table->longText('policy_content_1')->nullable();
            $table->longText('policy_content_2')->nullable();
            $table->boolean('show_cnic_on_badge')->default(true);
            $table->boolean('show_contact_on_badge')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
