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
        Schema::create('organization_staff', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('code')->unique();
            $table->string('staff_first_name');
            $table->string('staff_last_name');
            $table->string('staff_father_name');
            $table->string('staff_designation')->nullable();
            $table->string('staff_department')->nullable();
            $table->string('staff_job_type');
            $table->string('staff_nationality');
            $table->integer('staff_gender')->nullable();
            $table->string('staff_identity')->nullable()->unique();
            $table->date('staff_identity_expiry')->nullable();
            $table->string('staff_contact')->nullable()->unique();
            $table->string('staff_type');
            $table->string('staff_address')->nullable();
            $table->string('staff_city')->nullable();
            $table->string('staff_country')->nullable();
            $table->date('staff_dob')->nullable();
            $table->date('staff_doj')->nullable();
            $table->string('employee_type');
            $table->string('staff_status');
            $table->string('staff_security_status')->default('pending');
            $table->string('staff_remarks')->nullable();
            $table->uuid('company_uid');
            $table->integer('isPrinted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_staff');
    }
};
