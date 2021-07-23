<?php

namespace App\Models;

use App\Casts\LotterySelectionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LotteryDraw extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'lucky_selection' => LotterySelectionCast::class
    ];
}
