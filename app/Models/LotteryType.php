<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryType extends Model
{
    /**
     * TYPES OF LOTTERIES
     *
     * SIX_OF_FORTYNINE - 6/49 lottery type
     */

    const SIX_OF_FORTYNINE = 1;

    public $id;
    public $name;
    public $rules;

    public function __construct(int $id, string $name, array $rules)
    {
        $this->id = $id;
        $this->name = $name;
        $this->rules = $rules;
    }

    public function toArray(): array
    {
        return ['id' => $this->id, 'name' => $this->name, 'rules' => $this->rules];
    }

    public static function getAll()
    {
        return [
            new LotteryType(LotteryType::SIX_OF_FORTYNINE, 'sixOfFortynine', ['maxLength' => 6, 'range' => [1, 49]]),
        ];
    }

    public static function find($id)
    {
        foreach (LotteryType::getAll() as $item) {
            if ($item->id == $id) {
                return $item->toArray();
            }
        }
        return null;
    }
}
