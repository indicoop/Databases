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
        Schema::create('cooperatives', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->string('registration_number');
            $table->boolean('status')->default(true);
            $table->date('effective_date');
            $table->string('status_grade');
            $table->date('date_of_establishment');
            $table->text('address');
            $table->string('email');
            $table->string('phone_number');
            $table->string('form_of_cooperative');
            $table->text('certificate'); // file
            $table->text('legal_entity_certificate'); // file
            $table->text('profile_picture'); // file
            $table->boolean('is_verified')->default(false);
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
        Schema::dropIfExists('cooperatives');
    }
};
