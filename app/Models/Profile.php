<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = ['user'];

    protected $guarded = [];

    protected $table = 'profiles';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
