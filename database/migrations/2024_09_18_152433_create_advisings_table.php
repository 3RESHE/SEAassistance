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
        Schema::create('advisings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // The student being advised
            $table->unsignedBigInteger('advisor_id')->nullable(); 
            $table->integer('unit_limit');
            $table->string('advising_status');
            $table->string('load_status')->nullable(); // New column to indicate 'overload' or 'underload'
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Student
            $table->foreign('advisor_id')->references('id')->on('users')->onDelete('cascade'); // Advisor
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisings');
    }
};
