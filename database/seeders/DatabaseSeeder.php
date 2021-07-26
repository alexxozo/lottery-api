<?php

namespace Database\Seeders;

use App\Helpers\ManageLotteriesHelper;
use App\Models\Ballot;
use App\Models\BallotNumber;
use App\Models\Lottery;
use App\Models\LotteryDraw;
use App\Models\LotteryWinner;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $lotteries = [Lottery::factory()->create()];
        $admin = Profile::factory()->create(['user_id' => User::factory()->create(['is_admin' => true, 'email' => 'admin@example.com', 'password' => Hash::make('example')])]);
        $participant = Profile::factory()->create(['user_id' => User::factory()->create(['email' => 'user@example.com', 'password' => Hash::make('example')])]);

        $lotteryManager = new ManageLotteriesHelper();
        $lotteryDraw = $lotteryManager->registerRandomDraw($lotteries[0]->id);
        $lotteryManager->registerBallot($lotteries[0]->id, $participant->user->id, [1, 2, 3, 4, 5, 6]);

        $lotteryWinner = LotteryWinner::factory()->create([
            'lottery_draw_id' => $lotteryDraw,
            'user_id' => $participant->user->id
        ]);
    }
}
