<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->string('viber')->nullable()->after('phone');
            $table->string('itra')->nullable()->default('no')->after('has_medical_condition');
            $table->string('itra_details')->nullable()->after('itra');
        });
    }

    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn(['viber', 'itra', 'itra_details']);
        });
    }
};