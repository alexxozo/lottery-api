<?php

namespace Database\Factories;

use App\Models\LotteryDraw;
use App\Models\LotteryDrawNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotteryDrawNumberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LotteryDrawNumber::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lottery_draw_id' => function () {
                return LotteryDraw::factory()->create()->id;
            },
            'number' => $this->faker->randomNumber(2)
        ];
    }
}
