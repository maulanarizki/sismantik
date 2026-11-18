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
        Schema::table('sentiment_results', function (Blueprint $table) {
            $table->enum('framing_category', [
                'administrative',
                'corruption',
                'economic',
                'conflict',
                'human_interest'
            ])->default('administrative')->after('emotion_scores');
            $table->index('framing_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sentiment_results', function (Blueprint $table) {
            $table->dropIndex(['framing_category']);
            $table->dropColumn('framing_category');
        });
    }
};

