<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLastBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('last_bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sub_bank_account_id')->unsigned()->index();
            $table->string('name');
            $table->string('account_number');
            $table->timestamps();
        });

        Schema::table('last_bank_accounts', function($table) {
            $table->foreign('sub_bank_account_id')->references('id')->on('sub_bank_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('last_bank_accounts');
    }
}
