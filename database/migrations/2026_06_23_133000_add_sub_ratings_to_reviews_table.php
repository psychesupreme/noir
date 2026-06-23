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
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('quality_rating')->nullable()->after('rating');
            $table->unsignedTinyInteger('freshness_rating')->nullable()->after('quality_rating');
            $table->unsignedTinyInteger('value_rating')->nullable()->after('freshness_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['quality_rating', 'freshness_rating', 'value_rating']);
        });
    }
};
