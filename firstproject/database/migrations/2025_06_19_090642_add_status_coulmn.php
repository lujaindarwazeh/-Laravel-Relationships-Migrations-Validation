<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CourseStatus;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            //
            $table->enum('status',
            [
                CourseStatus::ACTIVE->getValue(),
                CourseStatus::COMPLETED->getValue(),
                CourseStatus::ARCHIVED->getValue()
            ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function (Blueprint $table) {

            $table->dropColumn('status');
            //
        });
    }
};
