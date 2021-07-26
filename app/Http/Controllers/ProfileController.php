<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;

class ProfileController extends BaseAbstractController
{
    public function __construct(ProfileService $service)
    {
        $rules = [
            'user_id' => 'required|numeric',
            'balance' => 'numeric',
            'ballots_count' => 'numeric',
            'wins' => 'numeric',
        ];
        parent::__construct($service, 'App\Models\Profile', $rules);
    }
}
