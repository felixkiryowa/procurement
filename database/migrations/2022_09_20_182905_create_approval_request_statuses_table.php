<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRequestStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_request_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('procurement_plan_detail_id')->unsigned()->index();
            $table->bigInteger('approver_id')->unsigned()->index();
            $table->integer('status')->default(0);
            $table->text('reason');
            $table->string('module');
            $table->timestamps();
        });

        Schema::table('approval_request_statuses', function($table) {
            $table->foreign('procurement_plan_detail_id')->references('id')->on('procurement_plan_details')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_request_statuses');
    }
}
