<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->string('installment_type');
            $table->integer('installment_number');
            $table->date('pay_date');
            $table->date('lateness_date')->nullable();
            $table->float('total_installment');
            $table->float('interest');
            $table->float('fine');
            $table->float('total_pay');
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
        Schema::dropIfExists('installments');
    }
};
