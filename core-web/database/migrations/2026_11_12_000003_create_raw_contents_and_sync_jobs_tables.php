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
        Schema::create('sync_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid('job_uuid');
            $table->foreignId('source_id')->nullable()->constrained('sources')->nullOnDelete();
            $table->foreignId('endpoint_id')->nullable()->constrained('source_endpoints')->nullOnDelete();
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->enum('triggered_by', ['system_cron', 'manual_user'])->default('system_cron');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_items_limit')->default(50);
            $table->integer('items_scraped')->default(0);
            $table->integer('items_processed')->default(0);
            $table->longText('log_messages')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('job_uuid');
        });

        Schema::create('raw_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_endpoint_id')->nullable()->constrained('source_endpoints')->nullOnDelete();
            $table->foreignId('sync_job_id')->nullable()->constrained('sync_jobs')->nullOnDelete();
            $table->string('external_id', 500)->nullable();
            $table->string('author', 255)->nullable();
            $table->string('title', 500)->nullable();
            $table->longText('content_text');
            $table->longText('clean_text')->nullable();
            $table->text('url');
            $table->char('content_hash', 64);
            $table->text('minhash_signature')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->foreignId('parent_id')->nullable()->constrained('raw_contents')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->json('engagement_metrics')->nullable();
            $table->boolean('is_analyzed')->default(false);
            $table->boolean('is_suspected_bot')->default(false);
            $table->string('bot_suspicion_reason', 255)->nullable();
            $table->timestamps();

            $table->index('content_hash');
            $table->index('published_at');
            $table->index('is_analyzed');
            $table->index('is_duplicate');
            $table->index('is_suspected_bot');
            $table->fullText(['title', 'content_text'], 'ft_title_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_contents');
        Schema::dropIfExists('sync_jobs');
    }
};

