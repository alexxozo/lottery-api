<?php

namespace Database\Factories;

use App\DTO\LotterySelectionDTO;
use App\Models\Ballot;
use App\Models\Lottery;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use phpDocumentor\Reflection\Utils;

class BallotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ballot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lottery_id' => function () {
                return Lottery::factory()->create()->id;
            },
            'user_id' => function () {
                return Profile::factory()->create()->user->id;
            },
        ];
    }
}
