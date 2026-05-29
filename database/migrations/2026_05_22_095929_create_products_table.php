<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('price'); // Managed in integer Ksh
            $table->integer('stock')->default(0);
            
            // Enterprise Inventory Classification
            $table->string('category')->default('retail'); // retail, wholesale, gifting
            $table->string('unit_type')->default('arrangement'); // arrangement, stem, bundle, hamper
            $table->string('grade')->nullable(); // Grade A (Export), Grade B (Standard), Grade C (Fillers)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};