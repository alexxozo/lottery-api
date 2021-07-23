<?php

namespace Database\Seeders;

use App\Models\Ballot;
use App\Models\Lottery;
use App\Models\LotteryDraw;
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
     */
    public function run()
    {
        $lotteries = [Lottery::factory()->create(), Lottery::factory()->create()];
        $admin = Profile::factory()->create(['user_id' => User::factory()->create(['is_admin' => true, 'email' => 'admin@example.com', 'password' => Hash::make('example')])]);
        $participant = Profile::factory()->create(['user_id' => User::factory()->create(['email' => 'user@example.com', 'password' => Hash::make('example')])]);
        $ballot = Ballot::factory()->create([
            'user_id' => $participant->user->id,
            'lottery_id' => $lotteries[0]->id,
        ]);
        $lotteryDraw = LotteryDraw::factory()->create([
            'lottery_id' => $lotteries[0]->id,
        ]);
    }
}
