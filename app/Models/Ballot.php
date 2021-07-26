<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ballot extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ballots';

    protected $with = ['numbers'];

    protected $guarded = [];

    public function numbers()
    {
        return $this->hasMany(BallotNumber::class);
    }

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
