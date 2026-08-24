<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'overall_capacity')) {
                $table->unsignedInteger('overall_capacity')
                    ->nullable()
                    ->after('status');
            }

            if (!Schema::hasColumn('events', 'creator_name')) {
                $table->string('creator_name')
                    ->nullable()
                    ->after('overall_capacity');
            }

            if (!Schema::hasColumn('events', 'creator_phone')) {
                $table->string('creator_phone')
                    ->nullable()
                    ->after('creator_name');
            }

            if (!Schema::hasColumn('events', 'english_waiver')) {
                $table->string('english_waiver')
                    ->nullable()
                    ->after('creator_phone');
            }

            if (!Schema::hasColumn('events', 'burmese_waiver')) {
                $table->string('burmese_waiver')
                    ->nullable()
                    ->after('english_waiver');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columns = [
                'overall_capacity',
                'creator_name',
                'creator_phone',
                'english_waiver',
                'burmese_waiver',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};