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
        // Add single-use batch tracking columns to promo_codes table
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('ticket_category_id');
            $table->integer('max_uses')->default(1)->after('discount_value');
            $table->integer('uses_count')->default(0)->after('max_uses');
            $table->enum('status', ['active', 'expired', 'disabled'])->default('active')->after('uses_count');
        });

        // Add promo_code_id and discount tracking to attendees table
        Schema::table('attendees', function (Blueprint $table) {
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete()->after('ticket_category_id');
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('promo_code_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropForeign(['promo_code_id']);
            $table->dropColumn(['promo_code_id', 'discount_amount']);
        });

        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'max_uses', 'uses_count', 'status']);
        });
    }
};