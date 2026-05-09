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
        Schema::create('trade_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('proposer_item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('receiver_item_id')->constrained('items')->cascadeOnDelete();
            $table->enum('status', ['pending', 'countered', 'accepted', 'declined', 'cancelled', 'meetup_scheduled', 'completed'])->default('pending');
            $table->unsignedBigInteger('cash_amount_cents')->nullable();
            $table->foreignId('cash_payer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('message')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('proposer_id');
            $table->index('receiver_id');
            $table->index('proposer_item_id');
            $table->index('receiver_item_id');
            $table->index('status');
        });

        Schema::create('trade_offer_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['created', 'countered', 'cash_changed', 'accepted', 'declined', 'cancelled', 'meetup_scheduled', 'completed']);
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('trade_offer_id');
            $table->index('actor_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_offer_events');
        Schema::dropIfExists('trade_offers');
    }
};
