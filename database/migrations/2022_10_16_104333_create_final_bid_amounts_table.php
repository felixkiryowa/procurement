<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalBidAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_bid_amounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('best_evaluated_bidder_id')->unsigned()->index();
            $table->bigInteger('amount');
            $table->string('currency');
            $table->timestamps();
        });

        Schema::table('final_bid_amounts', function($table) {
            $table->foreign('best_evaluated_bidder_id')->references('id')->on('best_evaluated_bidders')
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
        Schema::dropIfExists('final_bid_amounts');
    }
}
