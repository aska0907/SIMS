<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adv_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adv_student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->enum('semester', ['sem1', 'sem2']); // two semesters
            $table->integer('test1')->nullable();
            $table->integer('test2')->nullable();
            $table->integer('mid_term')->nullable();
            $table->integer('terminal')->nullable();
            $table->timestamps();

            $table->unique(['adv_student_id', 'subject_id', 'semester'], 'unique_grade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adv_grades');
    }
};
