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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');  
            $table->string('email');
            $table->string('password');
            $table->string('phone_num');
            $table->string('address');
            $table->string('operation_time')->nullable();
            $table->string('logo_pic')->nullable();
            $table->binary('license_pdf');
            $table->string('availability')->nullable();
            $table->string('description')->nullable();
            $table->string('status');            
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
        Schema::dropIfExists('restaurants');
    }
};
