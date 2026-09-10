<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role')->default('super_admin')->after('password'); // super_admin or event_admin
            $table->foreignId('event_id')->nullable()->after('role')->constrained('events')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn(['role', 'event_id']);
        });
    }
};