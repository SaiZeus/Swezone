<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('events', function (Blueprint $table) {
        $table->string('english_consent')->nullable()->after('burmese_waiver');
        $table->string('burmese_consent')->nullable()->after('english_consent');
    });
}

public function down()
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn(['english_consent', 'burmese_consent']);
    });
}
};
