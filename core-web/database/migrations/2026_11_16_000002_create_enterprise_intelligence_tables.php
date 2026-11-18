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
        // 1. TABEL PENGELOMPOKAN ISU BERBASIS SEMANTIK (ISSUE CLUSTERS)
        Schema::create('issue_clusters', function (Blueprint $table) {
            $table->id();
            $table->uuid('cluster_uuid')->unique();
            $table->string('title', 255);
            $table->enum('framing_category', ['administrative', 'corruption', 'economic', 'conflict', 'human_interest'])->default('administrative');
            $table->enum('lifecycle_stage', ['dormant', 'emerging', 'crisis', 'resolution'])->default('emerging');
            $table->integer('total_mentions')->default(0);
            $table->decimal('net_sentiment_score', 6, 4)->default(0.0000);
            $table->decimal('confidence_lower', 6, 4)->default(0.0000);
            $table->decimal('confidence_upper', 6, 4)->default(0.0000);
            $table->boolean('is_high_alert')->default(false);
            $table->timestamp('first_detected_at');
            $table->timestamp('last_active_at');
            $table->timestamps();

            $table->index('lifecycle_stage');
            $table->index('is_high_alert');
            $table->index('framing_category');
        });

        // 2. TABEL AKTOR & JARINGAN MEDIA SOSIAL (ACTOR NODES & SNA)
        Schema::create('actor_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 50);
            $table->string('username', 255);
            $table->string('display_name', 255)->nullable();
            $table->enum('account_type', ['verified_media', 'official_gov', 'kol_influencer', 'citizen_organic', 'suspected_buzzer'])->default('citizen_organic');
            $table->decimal('degree_centrality', 8, 4)->default(0.0000);
            $table->decimal('betweenness_centrality', 8, 4)->default(0.0000);
            $table->integer('total_interactions')->default(0);
            $table->decimal('bot_probability', 5, 4)->default(0.0000);
            $table->timestamps();

            $table->unique(['platform', 'username']);
            $table->index('account_type');
            $table->index('bot_probability');
        });

        // 3. TABEL MEMORANDUM INTELIJEN EKSEKUTIF (INTELLIGENCE BRIEFS)
        Schema::create('intelligence_briefs', function (Blueprint $table) {
            $table->id();
            $table->date('brief_date')->unique();
            $table->enum('threat_level', ['GREEN', 'YELLOW', 'ORANGE', 'RED'])->default('GREEN');
            $table->longText('executive_summary');
            $table->json('key_actors')->nullable();
            $table->json('actionable_recommendations');
            $table->longText('draft_press_release')->nullable();
            $table->string('generated_by', 100)->default('sismantik-intelligence-engine');
            $table->timestamps();

            $table->index('threat_level');
            $table->index('brief_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intelligence_briefs');
        Schema::dropIfExists('actor_nodes');
        Schema::dropIfExists('issue_clusters');
    }
};

