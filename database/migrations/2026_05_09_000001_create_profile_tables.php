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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('trust_score')->default(0);
            $table->unsignedInteger('response_time_minutes')->nullable();
            $table->unsignedInteger('completed_swaps_count')->default(0);
            $table->boolean('public_meetup_preferred')->default(true);
            $table->timestamps();

            $table->index('zip_code');
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('profile_wishlist_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tag');
            $table->timestamps();

            $table->index('user_id');
            $table->unique(['user_id', 'tag']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_wishlist_tags');
        Schema::dropIfExists('profiles');
    }
};
