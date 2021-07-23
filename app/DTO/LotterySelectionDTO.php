<?php


namespace App\DTO;


class LotterySelectionDTO extends DataTransferObject
{
    public $maxLength;
    public $numbersList;

    public static function fromArray(array $obj)
    {
        return new self([
            'maxLength' => $obj['maxLength'],
            'numbersList' => $obj['numbersList'],
        ]);
    }

    public static function fromObject(object $obj)
    {
        return new self([
            'maxLength' => $obj->maxLength,
            'numbersList' => $obj->numbersList,
        ]);
    }

    public static function toJSON(object $obj)
    {
        return json_encode([
            'maxLength' => $obj->maxLength,
            'numbersList' => $obj->numbersList,
        ]);
    }
}
