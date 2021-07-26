<?php


namespace App\Services;


use App\DTO\LotterySelectionDTO;
use App\Helpers\GlobalHelper;
use App\Helpers\ManageLotteriesHelper;
use App\Interfaces\IService;
use App\Models\Ballot;
use App\Models\LotteryDraw;
use App\Models\LotteryType;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class LotteryService extends IService
{

    /**
     * Create a new ballot for a participant in a lottery.
     *
     * @param array $fields
     * @return array|\string[][]
     */
    public function register(array $fields)
    {
        $lottery = $fields['lottery'];
        $participant = $fields['participant'];
        $lotteryType = LotteryType::find($lottery->type_id);
        $lotterySelection = $fields['selection'];
        // check if lottery is still available
        if ($lottery->end_date < Carbon::now()) {
            return ['errors' => ['Sorry lottery participation has ended.']];
        }
        // check if amount is enough for user to buy one entry
        if ($participant->balance <= $lottery->ballot_price) {
            return ['errors' => ['Sorry there is not enough money in your balance to fulfill this request.']];
        }
        // check if number list is valid for current rules of lottery
        if (count($lotterySelection) !== (int)$lotteryType['rules']['maxLength'] ||
            !GlobalHelper::checkArrayIsInRange($lotterySelection, $lotteryType['rules']['range'])) {
            return ['errors' => ['Sorry the number list is invalid.']];
        }

        // create entry and change balance
        $lotteryManagement = new ManageLotteriesHelper();
        try {
            DB::beginTransaction();
            $ballot = $lotteryManagement->registerBallot($lottery->id, $participant->user->id, $lotterySelection);
            $participant->balance = $participant->balance - $lottery->ballot_price;
            $participant->ballots_count += 1;
            if (!$participant->save()) {
                throw new \Exception('There was a problem saving.');
            }
            DB::commit();
            return ['errors' => [], 'obj_id' => $ballot->id];
        } catch (\Exception $e) {
            DB::rollback();
            return ['errors' => [$e->getMessage()]];
        }
    }

    /**
     * Return winner ballot from a specific date
     * @param array $fields
     * @return array|\string[][]
     */
    public function winnerBallot(array $fields)
    {
        $lottery = $fields['lottery'];
        $dayBefore = CarbonImmutable::create($fields['year'], $fields['month'], $fields['day']);
        $dayAfter = CarbonImmutable::create($fields['year'], $fields['month'], $fields['day'])->add('+2 days');
        $lotteryBallot = LotteryDraw::where('created_at', '>=', $dayBefore)
            ->where('created_at', '<=', $dayAfter)
            ->where('lottery_id', $lottery->id)
            ->first();
        if ($lotteryBallot) {
            return ['errors' => [], 'ballot' => ['id' => $lotteryBallot->id, 'numbers' => $lotteryBallot->numbers->pluck('number')]];
        } else {
            return ['errors' => ['Sorry there is no winner ballot for this date.']];
        }
    }
}
