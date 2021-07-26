<?php

namespace App\Http\Controllers;

use App\Models\Lottery;
use App\Models\Profile;
use App\Services\LotteryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LotteryController extends BaseAbstractController
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
        parent::__construct($service, 'App\Models\Lottery', $rules);
    }

    /**
     * Registers a user to a lottery by creating a new ballot.
     *
     * @param int $lotteryId
     * @param Request $request
     * @return JsonResponse
     */
    public function register($lotteryId, Request $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->first();
        if ($profile === null) {
            return response()->json(['message' => 'User not found.'], env('HTTP_NOT_FOUND'));
        }
        $lottery = Lottery::find($lotteryId);
        if ($lottery === null) {
            return response()->json(['message' => 'Lottery not found.'], env('HTTP_NOT_FOUND'));
        }

        $fields = $request->toArray();
        $rules = [
            'selection' => 'required|array',
        ];
        $validator = Validator::make($fields, $rules);
        $validatorErrors = $validator->errors()->all();
        if (sizeof($validatorErrors) == 0) {
            $fields['participant'] = $profile;
            $fields['lottery'] = $lottery;
            $result = $this->service->register($fields);
            if (sizeof($result["errors"]) > 0) {
                return response()->json(["message" => $result["errors"]], env("HTTP_BUSINESS_ERROR"));
            }
            return response()->json(["obj_id" => $result['obj_id']], env("HTTP_SUCCESS"));
        } else {
            return response()->json([['message' => $validatorErrors]], env("HTTP_VALIDATION_ERROR"));
        }
    }

    /**
     * Show the winner ballot for a specific date.
     *
     * @param $lotteryId
     * @param Request $request
     * @return JsonResponse
     */
    public function winnerBallot($lotteryId, Request $request)
    {
        $lottery = Lottery::find($lotteryId);
        if ($lottery === null) {
            return response()->json(['message' => 'Lottery not found.'], env('HTTP_NOT_FOUND'));
        }

        $fields = $request->all();
        $rules = [
            'year' => 'required|numeric',
            'month' => 'required|numeric',
            'day' => 'required|numeric'
        ];
        $validator = Validator::make($fields, $rules);
        $validatorErrors = $validator->errors()->all();
        if (sizeof($validatorErrors) == 0) {
            $fields['lottery'] = $lottery;
            $result = $this->service->winnerBallot($fields);
            if (sizeof($result["errors"]) > 0) {
                return response()->json(["message" => $result["errors"]], env("HTTP_BUSINESS_ERROR"));
            }
            return response()->json(["ballot" => $result['ballot']], env("HTTP_SUCCESS"));
        } else {
            return response()->json([['message' => $validatorErrors]], env("HTTP_VALIDATION_ERROR"));
        }
    }
}
