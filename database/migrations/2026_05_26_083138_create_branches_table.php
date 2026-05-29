<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Nairobi Central Atelier", "Kiambu Ridge Hub"
            $table->string('code')->unique(); // e.g., "NB-NBO", "NB-KBU"
            $table->string('location_city'); // Nairobi or Kiambu
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};