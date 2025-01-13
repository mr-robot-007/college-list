<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('center_id');
            $table->unsignedBigInteger('university_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('total')->nullable();
            $table->string('passout')->nullable();
            $table->decimal('fees1_amount')->nullable();
            $table->decimal('fees2_amount')->nullable();
            $table->decimal('fees3_amount')->nullable();
            $table->decimal('fees4_amount')->nullable();
            $table->decimal('fees5_amount')->nullable();
            $table->date('fees1_date')->nullable();
            $table->date('fees2_date')->nullable();
            $table->date('fees3_date')->nullable();
            $table->date('fees4_date')->nullable();
            $table->date('fees5_date')->nullable();
            $table->string('fees1_trans_id')->nullable();
            $table->string('fees2_trans_id')->nullable();
            $table->string('fees3_trans_id')->nullable();
            $table->string('fees4_trans_id')->nullable();
            $table->string('fees5_trans_id')->nullable();
            $table->string('fees1_status')->nullable();
            $table->string('fees2_status')->nullable();
            $table->string('fees3_status')->nullable();
            $table->string('fees4_status')->nullable();
            $table->string('fees5_status')->nullable();
            $table->string('student_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['Active', 'Deleted'])->default("Active");
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->default('1'); // Use unsignedBigInteger for foreign keys
            $table->unsignedBigInteger('updated_by')->default('1');
            $table->unsignedBigInteger('deleted_by')->default('1');
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
        Schema::dropIfExists('admissions_info');
    }
}
