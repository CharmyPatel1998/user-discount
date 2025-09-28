<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('discount_id')
                  ->constrained('discounts')
                  ->cascadeOnDelete();
            $table->unsignedInteger('usage_count')->default(0); // track usage per user
            $table->unsignedInteger('usage_limit')->nullable(); // null = unlimited usage
            $table->timestamps();

            $table->unique(['user_id', 'discount_id']); // ensure no duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_discounts');
    }
};
