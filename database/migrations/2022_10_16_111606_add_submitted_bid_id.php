<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmittedBidId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('best_evaluated_bidders', function (Blueprint $table) {
            $table->bigInteger('submitted_bid_id')->unsigned()->index();
        });

        Schema::table('best_evaluated_bidders', function($table) {
            $table->foreign('submitted_bid_id')->references('id')
            ->on('submitted_bids')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('best_evaluated_bidders', function (Blueprint $table) {
            //
        });
    }
}
