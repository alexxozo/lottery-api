<?php

namespace Database\Factories;

use App\Models\LotteryDraw;
use App\Models\LotteryWinner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotteryWinnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LotteryWinner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create();
            },
            'lottery_draw_id' => function () {
                return LotteryDraw::factory()->create();
            },
            'amount' => $this->faker->randomNumber(6)
        ];
    }
}
