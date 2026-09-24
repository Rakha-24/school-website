<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('teacher_number')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('grade');
            $table->string('academic_year');
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('status')->default('active')->index();
            $table->unique(['name', 'academic_year']);
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('student_number')->unique();
            $table->string('national_student_number')->nullable()->unique();
            $table->foreignId('class_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('enrollment_year');
            $table->string('photo')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('teachers');
    }
};