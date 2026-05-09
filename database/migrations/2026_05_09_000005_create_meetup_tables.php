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
        Schema::create('meetups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_offer_id')->constrained()->cascadeOnDelete();
            $table->string('location_name')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('safety_checklist_acknowledged_by_proposer')->default(false);
            $table->boolean('safety_checklist_acknowledged_by_receiver')->default(false);
            $table->timestamp('proposer_arrived_at')->nullable();
            $table->timestamp('receiver_arrived_at')->nullable();
            $table->enum('status', ['proposed', 'confirmed', 'arrived', 'completed', 'cancelled'])->default('proposed');
            $table->timestamps();

            $table->index('trade_offer_id');
            $table->index('scheduled_at');
            $table->index('status');
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('swap_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meetup_id')->constrained()->cascadeOnDelete();
            $table->string('qr_token_hash')->unique();
            $table->foreignId('generated_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('scanned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['active', 'scanned', 'expired', 'revoked'])->default('active');
            $table->timestamps();

            $table->index('trade_offer_id');
            $table->index('meetup_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_confirmations');
        Schema::dropIfExists('meetups');
    }
};
