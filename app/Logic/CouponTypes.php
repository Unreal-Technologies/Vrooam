<?php

namespace App\Logic;

enum CouponTypes: int
{
    use EnumInfo;

    case Flat = 1;
    case Percentage = 2;
}
