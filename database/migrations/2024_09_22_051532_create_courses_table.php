<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',255);
            $table->string('type',255)->nullable();
            $table->string('duration',255)->nullable();
            $table->string('visit',255)->nullable();
            $table->string('passout_1',255)->nullable();
            $table->string('passout_2',255)->nullable();
            $table->string('passout_3',255)->nullable();
            $table->string('passout_4',255)->nullable();
            $table->string('passout_5',255)->nullable();
            $table->string('passout_6',255)->nullable();
            $table->string('passout_7',255)->nullable();
            $table->string('passout_8',255)->nullable();
            $table->string('passout_9',255)->nullable();
            $table->string('passout_10',255)->nullable();
            $table->string('fees_1',255)->nullable();
            $table->string('fees_2',255)->nullable();
            $table->string('fees_3',255)->nullable();
            $table->string('fees_4',255)->nullable();
            $table->string('fees_5',255)->nullable();
            $table->string('fees_6',255)->nullable();
            $table->string('fees_7',255)->nullable();
            $table->string('fees_8',255)->nullable();
            $table->string('fees_9',255)->nullable();
            $table->string('fees_10',255)->nullable();
            $table->enum('status', ['Active', 'Inactive','Blocked', 'Deleted'])->default('Active');
            $table->unsignedBigInteger('institute_id')->default('1'); 
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
        Schema::dropIfExists('courses');
    }
}
