<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Coupon code, e.g., SAVE50
            $table->enum('type', ['flat', 'percent']); // Discount type
            $table->decimal('amount', 10, 2); // Discount amount or percent
            $table->decimal('min_total', 10, 2)->nullable(); // Minimum total for validity
            $table->boolean('active')->default(true); // Is coupon active
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
