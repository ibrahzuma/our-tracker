<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('contact_name');
            $table->enum('type', ['lent', 'borrowed']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance', 15, 2);
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending'); // pending, partially_paid, paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
