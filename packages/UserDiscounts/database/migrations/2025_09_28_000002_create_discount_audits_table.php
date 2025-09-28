<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('discount_id')
                  ->constrained('discounts')
                  ->cascadeOnDelete();
            $table->decimal('applied_amount', 10, 2); // how much discount was applied
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_audits');
    }
};
