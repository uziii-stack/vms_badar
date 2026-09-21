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
        Schema::create('depo_groups', function (Blueprint $table) {
            $table->uuid('uid')->primary();
            $table->bigInteger('staff_quantity');
            // $table->bigInteger('depo_category')->nullable();
            $table->foreignId('depo_category')->constrained('hr_categories')->onUpdate('no action')->onDelete('restrict');
            $table->string('depo_rep_name');
            $table->string('depo_rep_contact');
            $table->string('depo_rep_email')->nullable()->unique();
            $table->string('depo_rep_phone')->nullable();
            $table->uuid('depo_rep_uid')->nullable()->unique();
            $table->integer('depo_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depo_groups');
    }
};
