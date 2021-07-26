<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BallotNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ballots_numbers';

    protected $guarded = [];

}
