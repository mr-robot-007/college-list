<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToAdmissionsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admissions_info', function (Blueprint $table) {
            $table->string('enrollment_number')->nullable();
            $table->string('roll_number')->nullable();
            $table->date('dob')->nullable();
            $table->longText('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admissions_info', function (Blueprint $table) {
            //
        });
    }
}
