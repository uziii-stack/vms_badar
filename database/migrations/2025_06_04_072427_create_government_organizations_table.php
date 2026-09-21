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
        Schema::create('government_organizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('name');
            $table->string('address');
            $table->unsignedBigInteger('country');
            $table->unsignedBigInteger('city');
            $table->string('code');
            $table->unsignedBigInteger('group');
            $table->integer('ref_no')->nullable();
            $table->integer('allowed_quantity')->default(1);
            $table->integer('status')->default(1);
            $table->string('head_name');
            $table->bigInteger('head_contact');
            $table->string('head_email');
            $table->timestamps();
            $table->foreign('group')->references('id')->on('groups')
                ->onUpdate('no action')->onDelete('restrict');
            $table->foreign('country')->references('id')->on('countries')
                ->onUpdate('no action')->onDelete('restrict');
                $table->foreign('city')->references('id')->on('cities')
                ->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_organizations');
    }
};
