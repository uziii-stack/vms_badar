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
        Schema::create('invitees', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('name')->unique();
            $table->uuid('ranks_uid')->unique();
            $table->string('designation');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->foreign('ranks_uid')->references('ranks_uid')->on('ranks')
                ->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitees');
    }
};
