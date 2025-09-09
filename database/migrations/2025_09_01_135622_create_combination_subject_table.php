<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combination_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combination_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['core', 'optional'])->default('core');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combination_subject');
    }
};
