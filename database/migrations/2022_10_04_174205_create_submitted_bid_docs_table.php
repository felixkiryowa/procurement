<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittedBidDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_bid_docs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('submitted_bid_id')->unsigned()->index();
            $table->string('document');
            $table->string('tracking_number')->index();
            $table->bigInteger('created_by')->unsigned()->index();
            $table->bigInteger('last_updated_by')->index()->nullable();
            $table->timestamps();
        });

        Schema::table('submitted_bid_docs', function($table) {
            $table->foreign('submitted_bid_id')->references('id')->on('submitted_bids')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submitted_bid_docs');
    }
}
