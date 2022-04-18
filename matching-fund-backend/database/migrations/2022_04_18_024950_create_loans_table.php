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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('loan_date');
            $table->float('amount');
            $table->float('installment_principal');
            $table->float('installment_interest');
            $table->float('total_installment');
            $table->integer('installment_remaining');
            $table->integer('loan_type_id');
            $table->timestamps();
            $table->foreign('loan_type_id')->references('id')->on('loan_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
