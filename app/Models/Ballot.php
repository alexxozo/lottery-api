<?php

namespace App\Models;

use App\Casts\LotterySelectionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ballot extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ballots';

    protected $casts = [
        'lucky_selection' => LotterySelectionCast::class
    ];

    protected $fillable = [
        'user_id',
        'lottery_id',
        'lucky_selection'
    ];
}
