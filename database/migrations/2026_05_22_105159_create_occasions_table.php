<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occasions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Corporate Gala", "Valentine's Day"
            $table->string('slug')->unique();
            $table->string('accent_color'); // Holds hex or Tailwind text token (e.g., '#E11D48')
            $table->boolean('is_major_holiday')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occasions');
    }
};