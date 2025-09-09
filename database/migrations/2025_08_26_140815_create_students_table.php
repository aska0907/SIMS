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
    Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('class', ['Form 1', 'Form 2', 'Form 3', 'Form 4']);
            $table->string('registration_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
