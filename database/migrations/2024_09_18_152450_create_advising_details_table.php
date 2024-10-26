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
        Schema::create('advising_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advising_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('status'); // within_limit or exceeding_limit
            $table->integer('units');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('advising_id')->references('id')->on('advisings')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advising_details');
    }
};
