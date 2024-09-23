<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('university_name', 255);
            $table->string('approved_by', 255)->nullable();
            $table->string('university_website', 255)->nullable();
            $table->string('verification', 255)->nullable();
            $table->enum('status', ['Active', 'Inactive','Blocked', 'Deleted'])->default('Active');
            $table->unsignedBigInteger('created_by')->default('1'); // Use unsignedBigInteger for foreign keys
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
        Schema::dropIfExists('institutes');
    }
}
