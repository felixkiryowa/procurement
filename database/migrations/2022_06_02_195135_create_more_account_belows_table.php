<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoreAccountBelowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('more_account_belows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('account_below_bank_account_id')->unsigned()->index();
            $table->string('name');
            $table->string('account_number');
            $table->timestamps();
        });
        Schema::table('more_account_belows', function($table) {
            $table->foreign('account_below_bank_account_id')->references('id')
            ->on('last_bank_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('more_account_belows');
    }
}
