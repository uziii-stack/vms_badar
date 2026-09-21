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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('name', 50);
            $table->string('company')->nullable();
            $table->string('nationality')->nullable();
            $table->string('attandeePMDC')->nullable();
            $table->string('designation')->nullable();
            $table->string('job_title')->nullable();
            $table->string('sector')->nullable();
            $table->string('identity', 15)->unique();
            $table->string('contact')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('code')->nullable();
            $table->string('masterclass')->default('[]');
            $table->integer('day_1')->default(0);
            $table->integer('day_2')->default(0);
            $table->integer('day_3')->default(0);
            $table->integer('master_class')->default(0);
            $table->integer('seminar')->default(0);
            $table->integer('badge_print')->default(0);
            $table->integer('dupe_badge_print')->default(0);
            $table->timestamps();
            $table->integer('day_4')->default(0);
            $table->string('event')->default('INDUS AI WEEK');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
