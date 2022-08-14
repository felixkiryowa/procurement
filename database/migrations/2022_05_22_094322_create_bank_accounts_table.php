<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ledger_category_id')->unsigned()->index();
            $table->bigInteger('ledger_id')->unsigned()->index();
            $table->string('name');
            $table->string('account_number');
            $table->timestamps();
        });

        Schema::table('bank_accounts', function($table) {
            $table->foreign('ledger_id')->references('id')->on('ledgers')->onDelete('cascade');
            $table->foreign('ledger_category_id')->references('id')->on('ledger_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
