<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteriesDrawsNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotteries_draws_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lottery_draw_id');
            $table->foreign('lottery_draw_id')->references('id')->on('lottery_draws');
            $table->unsignedInteger('number');
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
        Schema::dropIfExists('lottery_draws_numbers');
    }
}
