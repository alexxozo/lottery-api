<?php

namespace App\Jobs;

use App\Helpers\ManageLotteriesHelper;
use App\Models\Ballot;
use App\Models\Lottery;
use App\Models\LotteryWinner;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\LoserNotification;
use App\Notifications\WinnerNotification;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SelectWinnerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $lottery;
    private $lotteryManagement;

    /**
     * Create a new job instance.
     *
     * @param $lottery_id
     * @param null $date - is used to select participants of a given day
     */
    public function __construct($lottery_id)
    {
        $this->lottery = Lottery::find($lottery_id);
        $this->lotteryManagement = new ManageLotteriesHelper();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        // Make a new random draw according to lottery rules
        $draw = $this->lotteryManagement->registerRandomDraw($this->lottery->id);
        $winningNumbers = $draw->numbers->pluck('number')->toArray();
        $winningNumbersString = implode(",", $winningNumbers);

        // [DEBUG] Because the odds of actually winning are very low, we can use a fake draw and see some results
        // $winningNumbersString = "1,2,3,4,5,6";

        $dayBeforeYesterday = CarbonImmutable::today()->add('-2 days')->format('Y-m-d');
        $dayAfterYesterday = CarbonImmutable::today()->format('Y-m-d');
        $queryString = "
            SELECT ballots.id, GROUP_CONCAT(ballots_numbers.number) AS winning_numbers, COUNT(ballots_numbers.number) AS cnt
            FROM ballots
            JOIN ballots_numbers ON (ballots.id = ballots_numbers.ballot_id and ballots_numbers.number IN (" . $winningNumbersString . "))
            WHERE ballots.created_at > TIMESTAMP(\"" . $dayBeforeYesterday . "\") AND ballots.created_at < TIMESTAMP(\"" . $dayAfterYesterday . "\")
            GROUP BY ballots.id
            HAVING cnt = " . $this->lottery->type['rules']['maxLength'];

        Log::debug($queryString);
        Log::info('A new random draw has been triggered at: ' . CarbonImmutable::now() . ' (draw_id: ' . $draw->id . ', numbers: [' . $winningNumbersString . ']) ');

        // Select winners (which have a ballot with the winning numbers)
        $ballotWinners = DB::select($queryString);
        $userWinners = [];
        if (count($ballotWinners) > 0) {
            // Add money to winner balance and notify them
            foreach ($ballotWinners as $winner) {
                try {
                    DB::beginTransaction();
                    $ballot = Ballot::find($winner->id);
                    $lotteryWinner = LotteryWinner::factory()->create([
                        'user_id' => $ballot->user->id,
                        'lottery_draw_id' => $draw->id,
                        'amount' => $this->lottery->prize
                    ]);
                    $userId = $ballot->user->id;
                    $userWinners [] = $userId;
                    $profile = Profile::where('user_id', $userId)->first();
                    $profile->balance += $this->lottery->prize;
                    $profile->wins += 1;
                    if (!$profile->save()) {
                        throw new \Exception('There was a problem saving the winner. (ballot_id: ' . $winner->id . ')');
                    }
                    DB::commit();
                    $profile->user->notify(new WinnerNotification($draw));
                    Log::info('User with id: ' . $ballot->user->id . ' won a prize of ' . $ballot->lottery->prize . ' (draw_id: ' . $draw->id . ', numbers: [' . $winningNumbersString . ']) ');
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw new \Exception('There was a problem saving the winner. (ballot_id: ' . $winner->id . ')');
                }
            }
        } else {
            Log::info('There is no winner :(. (draw_id: ' . $draw->id . ', numbers: [' . $winningNumbersString . ']) ');
        }

        // Notify users that did not win
        // Carbon::yesterday() is used because the job is triggered at midnight 00:00 and we need to get the participants from the day before
        // if needed this variable can be changed
        $participantUsers = $this->lottery->participants(CarbonImmutable::yesterday())->get();
        foreach ($participantUsers as $user) {
            if (!in_array($user->id, $userWinners)) {
                $user->notify(new LoserNotification($draw));
            }
        }

    }
}
