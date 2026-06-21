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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('gender');

            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('death_date')->nullable();
            $table->string('death_place')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
        Schema::create('person_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('name_type');
            $table->string('name');
            $table->start_date()->nullable();
            $table->end_date()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
