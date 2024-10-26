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
        Schema::create('curriculum_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->nullable()->constrained('curricula')->onDelete('set null');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->string('year')->nullable();
            $table->string('year_term')->nullable();
            $table->string('required_year_level')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_subjects');
    }
};
