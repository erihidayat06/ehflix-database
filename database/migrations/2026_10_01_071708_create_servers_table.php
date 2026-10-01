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
        Schema::create('servers', function ($table) {
            $table->id();
            $table->string('name'); // Contoh: VidStuck, VidLink, VidSrc
            $table->string('key')->unique(); // Contoh: vidstuck, vidlink, vidsrc
            $table->enum('type', ['movie', 'tv', 'both'])->default('both');

            // Pattern URL embed dengan placeholder {tmdb_id}, {season}, {episode}, {start}
            $table->text('movie_url_pattern')->nullable();
            $table->text('tv_url_pattern')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
