<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 60);
            $table->string('lastname', 60);
            $table->string('address', 120);
            $table->string('middlename', 60)->nullable();
            $table->foreignId('department_id')->references('id')->on('departments');
            $table->foreignId('city_id')->references('id')->on('cities');
            $table->foreignId('country_id')->references('id')->on('countries');
            $table->foreignId('state_id')->references('id')->on('states');
            $table->string('zip', 10);
            $table->date('birthdate')->nullable();
            $table->date('date_hired')->nullable();
            $table->timestamps();$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
