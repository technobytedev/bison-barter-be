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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->boolean('was_on_time')->nullable();
            $table->boolean('item_as_described')->nullable();
            $table->boolean('would_swap_again')->nullable();
            $table->timestamps();

            $table->unique(['trade_offer_id', 'reviewer_id']);
            $table->index('reviewed_user_id');
        });

        Schema::create('user_trust_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('source_type', ['trade_completed', 'review_received', 'report', 'verification']);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->integer('points');
            $table->string('reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('user_id');
            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_trust_events');
        Schema::dropIfExists('reviews');
    }
};
