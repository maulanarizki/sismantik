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
        Schema::table('raw_contents', function (Blueprint $table) {
            $table->enum('scope', ['local_pemkab', 'national_strategic'])
                ->default('local_pemkab')
                ->after('is_duplicate');
            $table->index('scope');
        });

        Schema::table('topics_or_agencies', function (Blueprint $table) {
            $table->enum('scope', ['local_pemkab', 'national_strategic'])
                ->default('local_pemkab')
                ->after('category');
            $table->index('scope');
        });

        Schema::table('source_endpoints', function (Blueprint $table) {
            $table->enum('scope', ['local_pemkab', 'national_strategic'])
                ->default('local_pemkab')
                ->after('parser_type');
            $table->index('scope');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_contents', function (Blueprint $table) {
            $table->dropIndex(['scope']);
            $table->dropColumn('scope');
        });

        Schema::table('topics_or_agencies', function (Blueprint $table) {
            $table->dropIndex(['scope']);
            $table->dropColumn('scope');
        });

        Schema::table('source_endpoints', function (Blueprint $table) {
            $table->dropIndex(['scope']);
            $table->dropColumn('scope');
        });
    }
};

