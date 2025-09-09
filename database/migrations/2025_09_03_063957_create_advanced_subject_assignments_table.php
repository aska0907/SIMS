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
    Schema::create('advanced_subject_assignments', function (Blueprint $table) {
        $table->id();
        
        // teacher
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        
        // subject assigned
        $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        
        // which class (form 5 or 6)
        $table->enum('class_level', ['Form 5', 'Form 6']);
        
        // which combination
        $table->foreignId('combination_id')->constrained()->onDelete('cascade');
        
        $table->timestamps();

        // prevent duplicate assignment
        $table->unique(['user_id', 'subject_id', 'class_level', 'combination_id'], 'unique_adv_subject');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advanced_subject_assignments');
    }
};
