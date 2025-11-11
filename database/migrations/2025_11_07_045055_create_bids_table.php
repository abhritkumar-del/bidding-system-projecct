<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['active', 'outbid', 'won', 'lost'])->default('active');
            $table->boolean('is_autobid')->default(false);
            $table->decimal('max_autobid_amount', 12, 2)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            
            $table->index(['auction_id', 'amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};