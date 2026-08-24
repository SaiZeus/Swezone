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
    Schema::table('events', function (Blueprint $table) {
        $table->string('english_race_guide')->nullable()->after('burmese_waiver');
        $table->string('burmese_race_guide')->nullable()->after('english_race_guide');
    });
}

public function down(): void
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn(['english_race_guide', 'burmese_race_guide']);
    });
}
};
