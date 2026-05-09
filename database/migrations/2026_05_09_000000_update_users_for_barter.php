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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('provider', ['google', 'apple', 'email'])->default('email')->after('password');
            $table->string('provider_id')->nullable()->after('provider');
            $table->string('avatar_url')->nullable()->after('provider_id');
            $table->string('phone')->nullable()->after('avatar_url');
            $table->enum('status', ['active', 'suspended', 'deleted'])->default('active')->after('phone');
            $table->timestamp('last_seen_at')->nullable()->after('status');

            $table->index('status');
            $table->index(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['provider', 'provider_id']);
            $table->dropColumn([
                'provider',
                'provider_id',
                'avatar_url',
                'phone',
                'status',
                'last_seen_at',
            ]);
        });
    }
};
