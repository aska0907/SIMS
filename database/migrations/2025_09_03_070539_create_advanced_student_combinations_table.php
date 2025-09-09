<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advanced_student_combinations', function (Blueprint $table) {
            $table->id();

            // student reference (adv_students table)
            $table->foreignId('adv_student_id')->constrained('adv_students')->onDelete('cascade');

            // combination reference
            $table->foreignId('combination_id')->constrained('combinations')->onDelete('cascade');

            // class level (Form 5 or Form 6)
            $table->enum('class_level', ['Form 5', 'Form 6']);

            $table->timestamps();

            // prevent duplicate assignment
            $table->unique(['adv_student_id', 'combination_id', 'class_level'], 'unique_adv_student_combination');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advanced_student_combinations');
    }
};
