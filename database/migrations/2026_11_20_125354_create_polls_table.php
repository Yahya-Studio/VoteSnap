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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug', 64)->unique();
            $table->string('title');
            $table->text('question');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('poll_type', 32)->default('single'); // 'single' or 'multiple'
            $table->unsignedTinyInteger('max_choices')->nullable()->default(1);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('visibility', 32)->default('public'); // 'public', 'unlisted'
            $table->boolean('show_results')->default(true);
            $table->string('results_visibility', 32)->default('always'); // 'always', 'after_vote', 'after_end'
            $table->boolean('allow_vote_change')->default(false);
            $table->boolean('randomize_options')->default(false);
            $table->boolean('is_closed_manually')->default(false);
            $table->dateTime('closed_at')->nullable();
            $table->unsignedInteger('votes_count')->default(0);
            $table->timestamps();

            $table->index(['visibility', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
