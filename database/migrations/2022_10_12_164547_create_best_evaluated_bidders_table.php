<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBestEvaluatedBiddersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('best_evaluated_bidders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tender_notice_id')->unsigned()->index();
            $table->string('provider');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->text('reason');
            $table->integer('best_evaluated_bidder')->default(0)->index();
            $table->timestamps();
        });

        Schema::table('best_evaluated_bidders', function($table) {
            $table->foreign('tender_notice_id')->references('id')->on('tender_notices')
            ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('best_evaluated_bidders');
    }
}
