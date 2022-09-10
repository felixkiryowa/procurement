<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId("organization_id")->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->date("financial_year_start");
            $table->date("financial_year_end");
            $table->string("title")->nullable();
            $table->text("details")->nullable();
            $table->string("document_url")->nullable();
            $table->enum('status', ['saved', 'published', 'archived'])->default('saved');
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
        Schema::dropIfExists('procurement_plans');
    }
}
