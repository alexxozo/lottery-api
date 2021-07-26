<?php


namespace App\Helpers;

use App\Models\Ballot;
use App\Models\BallotNumber;
use App\Models\Lottery;
use App\Models\LotteryDraw;
use App\Models\LotteryDrawNumber;
use App\Models\LotteryType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ManageLotteriesHelper
{
    public function __construct()
    {
    }

    /**
     * Random draw with regards of what type of lottery is playing.
     *
     * @param Lottery $lottery
     * @return mixed
     * @throws \Exception
     */
    public function randomDraw($lottery)
    {
        $method = 'handle' . Str::studly(str_replace('.', '_', LotteryType::find($lottery->type_id)['name']));
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        throw new \Exception('No method for this type.');
    }

    /**
     * Random draw for 6/49 lottery.
     * @return array
     */
    public function handleSixOfFortynine()
    {
        $selection = [];
        for ($i = 0; $i < 6; $i++) {
            $selection[] = rand(1, 49);
        }
        return $selection;
    }

    /**
     * Register a new ballot for a specific profile in a lottery.
     * @param int $lottery_id
     * @param int $user_id
     * @param array $selection
     * @return Collection|Model
     */
    public function registerBallot($lottery_id, $user_id, $selection)
    {
        $ballot = Ballot::factory()->create([
            'user_id' => $user_id,
            'lottery_id' => $lottery_id,
        ]);
        foreach ($selection as $number) {
            $ballotNumber = BallotNumber::factory()->create([
                'ballot_id' => $ballot->id,
                'number' => $number
            ]);
            $ballot->numbers()->save($ballotNumber);
        }
        return $ballot;
    }

    /**
     * Register a new LotteryDraw for a lottery.
     * @param $lottery_id
     * @return Collection|Model
     * @throws \Exception
     */
    public function registerRandomDraw($lottery_id)
    {
        $lottery = Lottery::find($lottery_id);
        $selection = $this->randomDraw($lottery);
        $lotteryDraw = LotteryDraw::factory()->create([
            'lottery_id' => $lottery_id,
        ]);
        foreach ($selection as $number) {
            $lotteryDrawNumber = LotteryDrawNumber::factory()->create([
                'lottery_draw_id' => $lotteryDraw->id,
                'number' => $number
            ]);
            $lotteryDraw->numbers()->save($lotteryDrawNumber);
        }
        return $lotteryDraw;
    }
}
