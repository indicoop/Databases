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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('business_detail_id');
            $table->integer('product_category_id');
            $table->string('name');
            $table->float('price');
            $table->integer('stock');
            $table->text('description');
            $table->string('thumbnail');
            $table->date('production_date');
            $table->float('discount')->nullable();
            $table->float('weight')->nullable();
            $table->integer('voucher_id')->nullable();
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
        Schema::dropIfExists('products');
    }
};
