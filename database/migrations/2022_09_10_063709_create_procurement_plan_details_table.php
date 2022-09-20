<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementPlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_plan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("plan_id")->constrained('procurement_plans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId("category_id")->constrained('procurement_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId("method_id")->constrained('procurement_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->BigInteger('amount')->nullable();
            $table->date('C')->nullable();
            $table->date('E')->nullable();
            $table->date('F')->nullable();
            $table->date('G')->nullable();
            $table->date('H')->nullable();
            $table->date('I')->nullable();
            $table->date('J')->nullable();
            $table->date('K')->nullable();
            $table->date('L')->nullable();
            $table->date('M')->nullable();
            $table->date('N')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procurement_plan_details');
    }
}
