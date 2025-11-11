<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('current_price', 12, 2)->default(0);
            $table->decimal('bid_increment', 12, 2)->default(100);
            $table->enum('status', ['scheduled', 'active', 'ended', 'cancelled'])->default('scheduled');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('total_bids')->default(0);
            $table->boolean('reserve_met')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};