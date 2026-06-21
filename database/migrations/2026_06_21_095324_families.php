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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();

            $table->foreignId('spouse1_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignId('spouse2_id')->nullable()->constrained('people')->nullOnDelete();

            $table->string('marriage_date')->nullable();
            $table->date('marriage_date_parsed')->nullable();
            $table->string('marriage_place')->nullable();

            $table->string('divorce_date')->nullable();
            $table->date('divorce_date_parsed')->nullable();
            $table->string('divorce_place')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
        Schema::create('family_child', function (Blueprint $table) {
            $table->foreignId('family_id')->constrained('families')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('pedigree')->nullable(); // birth, adopted, foster, sealed
            $table->primary(['family_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_child');
        Schema::dropIfExists('families');
    }
};
