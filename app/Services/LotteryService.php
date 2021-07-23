<?php


namespace App\Services;


use App\DTO\LotterySelectionDTO;
use App\Helpers\HelperFunctions;
use App\Interfaces\IService;
use App\Models\Ballot;
use App\Models\LotteryType;
use Carbon\Carbon;
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
        $lotteryEntry = LotterySelectionDTO::fromArray([
            'maxLength' => $lotteryType->rules['maxLength'],
            'numbersList' => array_unique($fields['numbers_list'])
        ]);
        // check if lottery is still available
        if ($lottery->end_date < Carbon::now()) {
            return ['errors' => ['Sorry lottery participation has ended.']];
        }
        // check if amount is enough for user to buy one entry
        if ($participant->balance <= $lottery->ballot_price) {
            return ['errors' => ['Sorry there is not enough money in your balance to fulfill this request.']];
        }
        // check if number list is valid for current rules of lottery
        if (count($lotteryEntry->numbersList) !== (int)$lotteryEntry->maxLength ||
            !HelperFunctions::checkArrayIsInRange($lotteryEntry->numbersList, $lotteryType->rules['range'])) {
            return ['errors' => ['Sorry the number list is invalid.']];
        }

        // create entry and change balance
        try {
            DB::beginTransaction();
            $ballot = Ballot::create([
                'user_id' => $participant->user->id,
                'lottery_id' => $lottery->id,
                'lucky_selection' => $lotteryEntry
            ]);
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

}
