<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('sub_bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bank_account_id')->unsigned()->index();
            $table->string('name');
            $table->string('account_number');
            $table->timestamps();
        });

        Schema::table('sub_bank_accounts', function($table) {
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_bank_accounts');
    }
}
