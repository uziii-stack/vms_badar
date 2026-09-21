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
        Schema::create('government_staff', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->uuid('ranks_uid');
            $table->uuid('govt_org_uid');
            $table->string('name');
            $table->string('designation');
            $table->string('identity')->unique();
            $table->string('address');
            $table->bigInteger('contact');
            $table->string('code');
            $table->unsignedBigInteger('invited_by');
            $table->unsignedBigInteger('staff_category')->nullable();
            $table->uuid('picture')->nullable();
            $table->unsignedBigInteger('country');
            $table->unsignedBigInteger('city');
            $table->string('car_sticker_color')->nullable();
            $table->string('car_sticker_no')->nullable();
            $table->string('invitaion_no')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->foreign('invited_by')->references('id')->on('invitees')
                ->onUpdate('no action')->onDelete('restrict');
            $table->foreign('staff_category')->references('id')->on('staff_categories')
                ->onUpdate('no action')->onDelete('restrict');
            $table->foreign('country')->references('id')->on('countries')
                ->onUpdate('no action')->onDelete('restrict');
            $table->foreign('city')->references('id')->on('cities')
                ->onUpdate('no action')->onDelete('restrict');
            $table->foreign('ranks_uid')->references('ranks_uid')->on('ranks')
                ->onUpdate('no action')->onDelete('restrict');
            $table->foreign('govt_org_uid')->references('uid')->on('government_organizations')
                ->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_staff');
    }
};
