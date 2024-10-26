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
        Schema::create('subject_corequisites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('corequisite_subject_id')->nullable();
            $table->timestamps();
        
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('corequisite_subject_id')->references('id')->on('subjects')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_corequisites');
    }
};
