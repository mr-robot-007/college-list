<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseVisitCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_visit_count', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default('1'); 
            $table->unsignedBigInteger('course_id')->default('1'); 
            $table->string('visit_count')->default(0);
            $table->enum('status', ['Active', 'Inactive','Blocked', 'Deleted'])->default('Active');
            $table->unsignedBigInteger('created_by')->default('1'); 
            $table->unsignedBigInteger('updated_by')->default('1');
            $table->unsignedBigInteger('deleted_by')->default('1');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_visit_count');
    }
}
