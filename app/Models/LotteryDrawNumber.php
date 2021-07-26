<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryDrawNumber extends Model
{
    use HasFactory;

    protected $table = 'lotteries_draws_numbers';

    protected $guarded = [];

}
