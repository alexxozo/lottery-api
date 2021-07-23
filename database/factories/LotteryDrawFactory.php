<?php

namespace Database\Factories;

use App\Models\Lottery;
use App\Models\LotteryDraw;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotteryDrawFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LotteryDraw::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lottery_id' => Lottery::factory()->create(),
            'lucky_selection' => ['maxLength' => 3, 'numbersList' => [1, 2, 3]]
        ];
    }
}
