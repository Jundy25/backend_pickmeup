<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRideHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('ride_histories', function (Blueprint $table) {
            $table->id('ride_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rider_id');
            $table->dateTime('ride_date');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->decimal('fare', 8, 2);
            $table->string('status');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('rider_id')->references('rider_id')->on('riders');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ride_histories');
    }
}

