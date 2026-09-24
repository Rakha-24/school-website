<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('content');
            $table->string('category');
            $table->string('cover_image')->nullable();
            $table->foreignId('author_id')->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('level');
            $table->unsignedSmallInteger('year');
            $table->string('category');
            $table->string('participant')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('photo')->nullable();
            $table->string('schedule')->nullable();
            $table->string('leader')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->string('image');
            $table->text('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('admission_information', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('published')->index();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('published')->index();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('admission_information');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('extracurriculars');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('news');
    }
};