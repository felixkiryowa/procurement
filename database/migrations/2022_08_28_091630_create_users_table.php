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
            $table->bigInteger('account_type_id')->unsigned()->index();
            $table->string('organisationName')->nullable();
            $table->string('procurementCategory')->nullable();
            $table->text('briefDescription')->nullable();
            $table->string('userName')->nullable();
            $table->text('password')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('companyPhoneNumber')->nullable();
            $table->text('secretQuestion')->nullable();
            $table->text('secretAnswer')->nullable();
            $table->integer('challengeAnswer')->nullable();
            $table->string('country')->nullable();
            $table->string('registrationNumber')->nullable();
            $table->string('taxId')->nullable();
            $table->string('codeSentToEmail')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('originCountry')->nullable();
            $table->string('region')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('last_session_id')->nullable();
            $table->dateTime('last_time_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function($table) {
            $table->foreign('account_type_id')->references('id')->on('roles')->onDelete('cascade');
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
