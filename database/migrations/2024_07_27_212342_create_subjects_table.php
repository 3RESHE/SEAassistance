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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('courses');
            $table->string('required_year_level')->nullable();
            $table->string('year_term')->nullable();
            $table->string('descriptive_title')->nullable();
            $table->string('subject_code')->nullable();
            $table->integer('units')->nullable();
            $table->string('year')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
