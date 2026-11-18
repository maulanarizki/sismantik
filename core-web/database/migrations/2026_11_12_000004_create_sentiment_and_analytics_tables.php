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
        Schema::create('sentiment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_content_id')->constrained('raw_contents')->cascadeOnDelete();
            $table->enum('sentiment_label', ['positive', 'neutral', 'negative']);
            $table->decimal('sentiment_score', 6, 4); // -1.0000 to +1.0000
            $table->decimal('confidence_score', 6, 4); // 0.0000 to 1.0000
            $table->string('emotion_label', 50)->nullable(); // marah, kecewa, takut, sedih, apresiasi, harapan
            $table->json('emotion_scores')->nullable();
            $table->string('analyzed_by_model', 100)->default('indobert-smsa-v1');
            $table->timestamps();

            $table->index('sentiment_label');
            $table->index('sentiment_score');
            $table->index('emotion_label');
        });

        Schema::create('aspect_sentiments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sentiment_result_id')->constrained('sentiment_results')->cascadeOnDelete();
            $table->foreignId('agency_id')->constrained('topics_or_agencies')->cascadeOnDelete();
            $table->string('aspect_keyword', 255)->nullable();
            $table->enum('aspect_sentiment_label', ['positive', 'neutral', 'negative']);
            $table->decimal('aspect_score', 6, 4);
            $table->timestamps();

            $table->index('aspect_sentiment_label');
        });

        Schema::create('geo_extractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_content_id')->constrained('raw_contents')->cascadeOnDelete();
            $table->string('district_name', 150)->nullable();
            $table->string('subdistrict_name', 150)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('match_confidence', 5, 4)->nullable();
            $table->timestamps();

            $table->index('district_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_extractions');
        Schema::dropIfExists('aspect_sentiments');
        Schema::dropIfExists('sentiment_results');
    }
};

