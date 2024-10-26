<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('school_id')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('name_suffix')->nullable();
            $table->string('role');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('curriculum_id')->nullable()->constrained('curricula')->onDelete('set null'); 
            $table->string('year')->nullable();
            $table->string('semester')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('student_type')->nullable();
            $table->string('user_status')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Insert default users
        DB::table('users')->insert([
            [
                'school_id' => '123456789admin',
                'name' => 'Admin',
                'last_name' => 'User',
                'email' => 'seaassistadmin@gmail.com',
                'role' => 'admin',
                // 'department_role' => null,
                'password' => Hash::make('12345678'),
                'year' => null,
                'semester' => null,
                'academic_year' => null,
                'student_type' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'school_id' => '123456789secretary',
                'name' => 'Secretary',
                'last_name' => 'User',
                'email' => 'seaassistsecretary@gmail.com',
                'role' => 'secretary',
                // 'department_role' => 'SecretaryCOI',
                'password' => Hash::make('12345678'),
                'year' => null,
                'semester' => null,
                'academic_year' => null,
                'student_type' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'school_id' => '123456789evaluator',
            //     'name' => 'Evaluator',
            //     'last_name' => 'User',
            //     'email' => 'seaassistevaluator@gmail.com',
            //     'role' => 'evaluator',
            //     // 'department_role' => 'EvaluatorCOI',
            //     'password' => Hash::make('12345678'),
            //     'year' => null,
            //     'semester' => null,
            //     'academic_year' => null,
            //     'student_type' => null,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'school_id' => '123456789student',
            //     'name' => 'Student',
            //     'last_name' => 'User',
            //     'email' => 'seaassiststudent@gmail.com',
            //     'role' => 'student',
            //     // 'department_role' => null,
            //     'password' => Hash::make('12345678'),
            //     'year' => '2nd',
            //     'semester' => '1',
            //     'academic_year' => '2024-2025',
            //     'student_type' => 'regular',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ]);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
