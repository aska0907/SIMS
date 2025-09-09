<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adv_students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('stream', ['Science', 'Arts']); // Stream for Advanced
            $table->enum('class', ['Form 5', 'Form 6']); // Advanced classes
            $table->string('registration_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adv_students');
    }
};
