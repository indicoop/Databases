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
        Schema::create('stashs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->float('beginning_balance');
            $table->float('ending_balance');
            $table->date('stash_date');
            $table->float('stash_amount');
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
        Schema::dropIfExists('stashs');
    }
};
