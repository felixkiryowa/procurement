<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('district')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->text('duty_station')->nullable();
            $table->string('bank')->nullable();
            $table->string('account_number')->nullable()->index();
            $table->string('telephone_number')->nullable();
            $table->string('code_in_coa')->nullable()->index();
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
        Schema::dropIfExists('beneficiaries');
    }
}
