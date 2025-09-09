<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('adv_grades', function (Blueprint $table) {
            $table->unsignedBigInteger('combination_id')->after('class');

            // If you want foreign key constraint (optional, safer):
            $table->foreign('combination_id')
                  ->references('id')->on('advanced_student_combinations')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('adv_grades', function (Blueprint $table) {
            $table->dropForeign(['combination_id']);
            $table->dropColumn('combination_id');
        });
    }
};
