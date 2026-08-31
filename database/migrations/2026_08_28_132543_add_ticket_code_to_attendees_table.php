<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            if (!Schema::hasColumn('attendees', 'ticket_uuid')) {
                $table->uuid('ticket_uuid')->nullable()->after('ticket_category_id');
            }
            if (!Schema::hasColumn('attendees', 'ticket_code')) {
                $table->string('ticket_code')->nullable()->after('ticket_uuid');
            }
            if (!Schema::hasColumn('attendees', 'user_code')) {
                $table->string('user_code')->nullable()->after('ticket_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn(['ticket_uuid', 'ticket_code', 'user_code']);
        });
    }
};