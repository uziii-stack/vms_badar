<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('type')->constrained('events')->nullOnDelete();
            $table->longText('section_4_content')->nullable()->after('foot_content');
        });
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_id');
            $table->dropColumn('section_4_content');
        });
    }
};
