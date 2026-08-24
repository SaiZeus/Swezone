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
        Schema::table('attendees', function (Blueprint $table) {
            $table->string('father_name')->nullable()->after('full_name');
            $table->string('emergency_contact')->nullable()->after('phone');
            $table->string('country')->nullable()->after('nrc_passport');
            $table->string('gender')->nullable()->after('country');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('bib_name', 10)->nullable()->after('date_of_birth');
            $table->string('blood_type')->nullable()->after('tshirt_size');
            $table->string('has_medical_condition')->default('no')->after('blood_type');
            $table->text('medical_details')->nullable()->after('has_medical_condition');
            $table->text('address')->nullable()->after('medical_details');
            $table->text('experience')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'emergency_contact',
                'country',
                'gender',
                'date_of_birth',
                'bib_name',
                'blood_type',
                'has_medical_condition',
                'medical_details',
                'address',
                'experience',
            ]);
        });
    }
};