<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittedBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_bids', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tender_notice_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('amount');
            $table->text('brief_description');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('currency');
            $table->text('uploaded_files');
            $table->string('status')->index();
            $table->timestamps();
        });

        Schema::table('submitted_bids', function($table) {
            $table->foreign('tender_notice_id')->references('id')->on('tender_notices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submitted_bids');
    }
}
