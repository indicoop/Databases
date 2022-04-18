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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('courier_id')->constrained();
            $table->foreignId('cooperative_id')->constrained();
            $table->float('total_pay');
            $table->foreignId('payment_method_id')->constrained();
            $table->string('status')->default('pending');
            $table->float('shipping_fee')->nullable();
            $table->date('transaction_date');
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
        Schema::dropIfExists('transaction_details');
    }
};
