<?php

namespace App\Models;

use App\Casts\LotterySelectionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LotteryDraw extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lottery_draws';

    protected $guarded = [];

    protected $casts = [
        'lucky_selection' => LotterySelectionCast::class
    ];

    public function numbers()
    {
        return $this->hasMany(LotteryDrawNumber::class);
    }

    public function lottery()
    {
        return $this->belongsTo(Lottery::class, 'lottery_id');
    }
}
