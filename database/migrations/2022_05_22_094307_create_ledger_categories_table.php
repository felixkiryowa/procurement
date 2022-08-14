<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ledger_id')->unsigned()->index();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::table('ledger_categories', function($table) {
            $table->foreign('ledger_id')->references('id')->on('ledgers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger_categories');
    }
}
