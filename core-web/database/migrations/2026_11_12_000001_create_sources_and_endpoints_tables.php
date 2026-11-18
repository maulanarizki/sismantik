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
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 100)->unique();
            $table->enum('platform_type', ['web_rss', 'web_html', 'facebook', 'instagram', 'tiktok', 'x']);
            $table->string('base_url', 500);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sync_interval_minutes')->default(60);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('platform_type');
        });

        Schema::create('source_endpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->string('name', 255);
            $table->text('endpoint_url');
            $table->enum('parser_type', ['auto_trafilatura', 'rss', 'playwright_custom', 'social_profile'])->default('auto_trafilatura');
            $table->json('selectors_config')->nullable();
            $table->integer('default_max_items')->default(50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_endpoints');
        Schema::dropIfExists('sources');
    }
};

