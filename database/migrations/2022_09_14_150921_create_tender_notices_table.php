<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_notices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('reference_number')->nullable();
            $table->foreignId("plan_id")->constrained('procurement_plans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId("subject_id")->constrained('procurement_plan_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId("category_id")->constrained('procurement_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId("method_id")->constrained('procurement_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->BigInteger('budget_amount')->nullable();
            $table->BigInteger('winner_bid_price')->nullable();
            $table->text('details')->nullable();
            $table->date('display_start_date')->nullable();
            $table->date('display_end_date')->nullable();
            $table->enum('type', ['works', 'services', 'goods'])->default('works');
            $table->enum('status', ['saved', 'published', 'cancelled','extended'])->default('saved');
            $table->string('document_url')->nullable();
            $table->dateTime('deadline')->nullable();
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
        Schema::dropIfExists('tender_notices');
    }
}
