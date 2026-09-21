<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'sponsor_picture')) {
                $table->string('sponsor_picture')->nullable()->after('picture');
            }

            if (!Schema::hasColumn('events', 'policy_content_1')) {
                $table->longText('policy_content_1')->nullable()->after('website');
            }

            if (!Schema::hasColumn('events', 'policy_content_2')) {
                $table->longText('policy_content_2')->nullable()->after('policy_content_1');
            }

            if (!Schema::hasColumn('events', 'show_cnic_on_badge')) {
                $table->boolean('show_cnic_on_badge')->default(true)->after('policy_content_2');
            }

            if (!Schema::hasColumn('events', 'show_contact_on_badge')) {
                $table->boolean('show_contact_on_badge')->default(true)->after('show_cnic_on_badge');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columns = [
                'sponsor_picture',
                'policy_content_1',
                'policy_content_2',
                'show_cnic_on_badge',
                'show_contact_on_badge',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
