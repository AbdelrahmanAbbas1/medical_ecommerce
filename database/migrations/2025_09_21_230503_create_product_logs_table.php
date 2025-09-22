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
        Schema::create('product_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['created', 'updated', 'deleted', 'force_deleted']);
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->json('changes')->nullable(); // Store old vs new values
            $table->timestamps();
            
            $table->index(['product_id', 'action']);
            $table->index('changed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_logs');
    }
};
