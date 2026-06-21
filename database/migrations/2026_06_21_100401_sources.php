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

            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('publication')->nullable();
            $table->string('url', 2048)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
        Schema::create('fact_sources', function (Blueprint $table) {
            $table->foreignId('fact_id')->constrained('facts')->cascadeOnDelete();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->string('citation_detail')->nullable();
            $table->primary(['fact_id', 'source_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_sources');
        Schema::dropIfExists('sources');
    }
};
