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
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('phone_number');
            $table->date('dob')->nullable()->after('gender');
            $table->json('settings')->nullable()->after('default_region');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('phone');
            $table->date('dob')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'dob', 'settings']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['gender', 'dob']);
        });
    }
};
