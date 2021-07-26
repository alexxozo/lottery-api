<?php

namespace App\Console\Commands;

use App\Jobs\SelectWinnerJob;
use App\Models\Lottery;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class DrawRandomWinnerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'draw:today_winner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It draws one random number set for all lotteries available today and notifies the participants.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lotteries = Lottery::where('start_date', '<=', CarbonImmutable::yesterday())
            ->where('end_date', '>=', CarbonImmutable::today())
            ->get();
        foreach ($lotteries as $lottery) {
            SelectWinnerJob::dispatch($lottery->id);
        }
    }
}
