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
            $table->string('source_title');
            $table->string('source_author')->nullable();
            $table->string('source_publication')->nullable();
            $table->string('source_url')->nullable();
            $table->text('source_notes')->nullable();

            $table->timestamps();
        });
        Schema::create('fact_sources', function (Blueprint $table) {
            $table->foreignId('fact_id')->constrained('facts')->cascadeOnDelete();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->primary(['fact_id', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
        Schema::dropIfExists('fact_sources');
    }
};
