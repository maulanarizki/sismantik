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
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('topic_id')->nullable()->constrained('topics_or_agencies')->nullOnDelete();
            $table->string('metric_type', 50)->default('negative_percentage_spike');
            $table->decimal('threshold_value', 6, 2)->default(40.00);
            $table->integer('window_hours')->default(2);
            $table->string('notification_target', 100)->default('telegram_group_humas');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });

        Schema::create('ai_executive_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('summary_date')->unique();
            $table->json('key_highlights');
            $table->json('root_causes');
            $table->json('recommended_actions');
            $table->text('raw_summary_text')->nullable();
            $table->timestamps();

            $table->index('summary_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_executive_summaries');
        Schema::dropIfExists('alert_rules');
    }
};

