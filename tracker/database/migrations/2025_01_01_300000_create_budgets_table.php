<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained(); // Only expense categories
            $table->decimal('amount', 10, 2); // Budget limit
            $table->string('month'); // YYYY-MM format
            $table->timestamps();
            $table->unique(['user_id', 'category_id', 'month']); // One budget per cat per month
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
