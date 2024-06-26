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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->constrained('users')->onDelete('cascade');
            $table->string('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->string('date');
            $table->string('time'); 
            $table->string('party_size');
            $table->string('table_num')->nullable();
            $table->string('remark')->nullable();
            $table->string('status');
            $table->string('completeness');
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
        Schema::dropIfExists('reservations');
    }
};
