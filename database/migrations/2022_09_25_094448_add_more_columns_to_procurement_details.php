<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToProcurementDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procurement_plan_details', function (Blueprint $table) {
            $table->enum('submitted', ['Yes', 'No'])->default('No');
            $table->integer('total_steps')->nullable();
            $table->integer('step')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procurement_plan_details', function (Blueprint $table) {
            //
        });
    }
}
