<?php

namespace App\Casts;

use App\DTO\ClassicLeaderboardData;
use App\DTO\LotterySelectionDTO;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class LotterySelectionCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        try {
            return LotterySelectionDTO::fromObject(json_decode($attributes['lucky_selection']));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     * @throws \Exception
     */
    public function set($model, $key, $value, $attributes)
    {
        // If value is a string then convert to obj
        if (gettype($value) === 'string') {
            $value = json_decode((string)$value);
        }

        try {
            return LotterySelectionDTO::toJSON((object)$value);
        } catch (\Exception $e) {
            throw new \Exception('Invalid content format.');
        }
    }
}
