<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'enabled_fields')) {
                $table->json('enabled_fields')->nullable()->after('overall_capacity');
            }
            if (!Schema::hasColumn('events', 'enable_bib_number')) {
                $table->boolean('enable_bib_number')->default(true)->after('overall_capacity');
            }
            if (!Schema::hasColumn('events', 'share_bib_prefix')) {
                $table->boolean('share_bib_prefix')->default(true)->after('enable_bib_number');
            }
            if (!Schema::hasColumn('events', 'event_bib_prefix')) {
                $table->string('event_bib_prefix', 3)->nullable()->after('share_bib_prefix');
            }
            if (!Schema::hasColumn('events', 'event_bib_start_number')) {
                $table->integer('event_bib_start_number')->default(1)->after('event_bib_prefix');
            }
        });

        Schema::table('ticket_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_categories', 'bib_prefix')) {
                $table->string('bib_prefix', 3)->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('ticket_categories', 'bib_start_number')) {
                $table->integer('bib_start_number')->default(1)->after('bib_prefix');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                Schema::hasColumn('events', 'enabled_fields') ? 'enabled_fields' : null,
                Schema::hasColumn('events', 'enable_bib_number') ? 'enable_bib_number' : null,
                Schema::hasColumn('events', 'share_bib_prefix') ? 'share_bib_prefix' : null,
                Schema::hasColumn('events', 'event_bib_prefix') ? 'event_bib_prefix' : null,
                Schema::hasColumn('events', 'event_bib_start_number') ? 'event_bib_start_number' : null,
            ]);

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('ticket_categories', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                Schema::hasColumn('ticket_categories', 'bib_prefix') ? 'bib_prefix' : null,
                Schema::hasColumn('ticket_categories', 'bib_start_number') ? 'bib_start_number' : null,
            ]);

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};