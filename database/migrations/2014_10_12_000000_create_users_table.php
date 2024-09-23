<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('type', ['Admin', 'AltAdmin','User'])->default('User');
            $table->enum('status', ['Active', 'Inactive', 'Blocked', 'Deleted'])->default("Active");
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->default('1'); // Use unsignedBigInteger for foreign keys
            $table->unsignedBigInteger('updated_by')->default('1');
            $table->unsignedBigInteger('deleted_by')->default('1');
            $table->enum('deleted_by_system', [0, 1])->default(0);
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
        Schema::dropIfExists('users');
    }
}
