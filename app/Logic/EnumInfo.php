<?php

namespace App\Logic;

trait EnumInfo
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function list(): array
    {
        return array_combine(self::values(), self::names());
    }

    public static function inverseList(): array
    {
        return array_combine(self::names(), self::values());
    }
}
