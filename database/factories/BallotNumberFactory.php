<?php

namespace Database\Factories;

use App\Models\Ballot;
use App\Models\BallotNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class BallotNumberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BallotNumber::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ballot_id' => function () {
                return Ballot::factory()->create()->id;
            },
            'number' => $this->faker->randomNumber(2)
        ];
    }
}
