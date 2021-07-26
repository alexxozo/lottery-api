<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lottery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lotteries';

    protected $appends = ['type'];

    protected $guarded = [];

    public function getTypeAttribute()
    {
        return LotteryType::find($this->type_id);
    }

    public function participants($date = null)
    {
        if ($date == null) {
            $date = Carbon::today();
        }
        $lottery = $this;
        return User::whereHas('ballots', function ($query) use ($lottery, $date) {
            $query->where('lottery_id', $lottery->id)->whereDate('created_at', $date);
//            $query->whereDate('created_at', $date);
        });
    }
}
