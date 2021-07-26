<?php

namespace App\Http\Controllers;

use App\Helpers\ManageLotteriesHelper;
use App\Models\Ballot;
use App\Services\BallotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BallotController extends BaseAbstractController
{
    public function __construct(BallotService $service)
    {
        $rules = [
            'lottery_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'selection' => 'array'
        ];
        parent::__construct($service, 'App\Models\Ballot', $rules);
    }

    /**
     * Store a newly created ballot in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $fields = $request->toArray();
        $validator = Validator::make($fields, $this->rules);
        $validatorErrors = $validator->errors()->all();
        if (sizeof($validatorErrors) == 0) {
            $lotteryManagementHelper = new ManageLotteriesHelper();
            $ballot = $lotteryManagementHelper->registerBallot($fields['lottery_id'], $fields['user_id'], $fields['selection']);
            return response()->json($ballot, env("HTTP_SUCCESS"));
        } else {
            return response()->json([['message' => $validatorErrors]], env("HTTP_VALIDATION_ERROR"));
        }
    }

    /**
     * Update the specified ballot in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        return null;
    }
}
