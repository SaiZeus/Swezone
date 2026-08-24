<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('enabled_fields')->nullable()->after('overall_capacity');
            $table->boolean('enable_bib_number')->default(true)->after('enabled_fields');
            $table->boolean('share_bib_prefix')->default(true)->after('enable_bib_number');
            $table->string('event_bib_prefix', 10)->nullable()->after('share_bib_prefix');
            $table->integer('event_bib_start_number')->default(1)->after('event_bib_prefix');
        });

        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->string('bib_prefix', 10)->nullable()->after('tickets_sold');
            $table->integer('bib_start_number')->default(1)->after('bib_prefix');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['enabled_fields', 'enable_bib_number', 'share_bib_prefix', 'event_bib_prefix', 'event_bib_start_number']);
        });

        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->dropColumn(['bib_prefix', 'bib_start_number']);
        });
    }
};