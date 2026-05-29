<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->integer('total_amount'); // Total order value in Ksh
            $table->string('status')->default('pending'); // pending, approved, processing, delivered, cancelled
            $table->text('special_instructions')->nullable(); // e.g., "Deliver to Westlands by 7:00 AM"
            $table->timestamp('required_delivery_at')->nullable(); // Target corporate timeline
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};