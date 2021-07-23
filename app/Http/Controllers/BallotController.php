<?php

namespace App\Http\Controllers;

use App\Services\LotteryService;

class BallotController extends BaseAbstractController
{
    public function __construct(LotteryService $service)
    {
        $rules = [
            'name' => 'required|string',
            'type_id' => 'required|numeric',
            'description' => 'required|string',
            'prize' => 'required|numeric',
            'ballot_price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',

        ];
        parent::__construct($service, 'App\Models\Ballot', $rules);
    }
}
