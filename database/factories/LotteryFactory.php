<?php

namespace Database\Factories;

use App\Models\Lottery;
use App\Models\LotteryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotteryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lottery::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1 months', 'now');
        $endDate = $this->faker->dateTimeBetween('+1 months', '+2 months');
        return [
            'name' => $this->faker->name(),
            'type_id' => LotteryType::SIX_OF_FORTYNINE,
            'description' => $this->faker->text(),
            'prize' => $this->faker->randomNumber(6),
            'ballot_price' => $this->faker->randomNumber(2),
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
}
