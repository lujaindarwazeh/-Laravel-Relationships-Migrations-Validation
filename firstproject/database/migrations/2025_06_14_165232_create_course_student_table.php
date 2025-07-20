<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_student', function (Blueprint $table) {
          



              $table->timestamps();
            $table->foreignId('course_id')
                ->constrained('course')
                ->onDelete('cascade');

            $table->foreignId('student_id')
                ->constrained('student')
                ->onDelete('cascade');
            $table->primary(['course_id', 'student_id'], 'course_student');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_student');
    }
};
