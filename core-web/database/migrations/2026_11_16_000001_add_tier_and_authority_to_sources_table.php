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
        Schema::table('sources', function (Blueprint $table) {
            $table->enum('tier', ['tier_1', 'tier_2', 'tier_3', 'tier_4'])->default('tier_2')->after('platform_type');
            $table->decimal('authority_score', 4, 2)->default(0.80)->after('tier');
            $table->index('tier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropIndex(['tier']);
            $table->dropColumn(['tier', 'authority_score']);
        });
    }
};

