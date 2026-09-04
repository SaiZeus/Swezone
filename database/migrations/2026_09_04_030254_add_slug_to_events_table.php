<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add nullable slug column first
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // 2. Populate slugs for existing rows using their titles (fallback to ID if title is missing)
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            $slug = Str::slug($event->title ?? 'event-' . $event->id);
            
            // Ensure uniqueness in case of duplicate titles
            $count = DB::table('events')->where('slug', $slug)->where('id', '!=', $event->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . $event->id;
            }

            DB::table('events')->where('id', $event->id)->update(['slug' => $slug]);
        }

        // 3. Now change column to unique and non-nullable
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};