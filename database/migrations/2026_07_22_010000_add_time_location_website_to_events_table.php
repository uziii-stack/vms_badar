<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'event_time')) {
                $table->string('event_time')->nullable()->after('end_date');
            }

            if (!Schema::hasColumn('events', 'event_location')) {
                $table->string('event_location')->nullable()->after('event_time');
            }

            if (!Schema::hasColumn('events', 'website')) {
                $table->string('website')->nullable()->after('event_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columns = ['event_time', 'event_location', 'website'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
