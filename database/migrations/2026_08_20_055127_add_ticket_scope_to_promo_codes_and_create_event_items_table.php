<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add ticket_category_id only if it does not already exist
        if (!Schema::hasColumn('promo_codes', 'ticket_category_id')) {
            Schema::table('promo_codes', function (Blueprint $table) {
                $table->foreignId('ticket_category_id')
                    ->nullable()
                    ->after('event_id')
                    ->constrained('ticket_categories')
                    ->nullOnDelete();
            });
        }

        // Create event_items only if it does not already exist
        if (!Schema::hasTable('event_items')) {
            Schema::create('event_items', function (Blueprint $table) {
                $table->id();

                $table->foreignId('event_id')
                    ->constrained('events')
                    ->onDelete('cascade');

                $table->string('title');

                $table->string('image');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('event_items')) {
            Schema::dropIfExists('event_items');
        }

        if (Schema::hasColumn('promo_codes', 'ticket_category_id')) {
            Schema::table('promo_codes', function (Blueprint $table) {
                // Drop foreign key only if it exists
                $table->dropForeign(['ticket_category_id']);
                $table->dropColumn('ticket_category_id');
            });
        }
    }
};