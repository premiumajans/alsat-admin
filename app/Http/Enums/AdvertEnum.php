<?php

namespace App\Http\Enums;

use Illuminate\Validation\Rules\Enum;

class AdvertEnum extends Enum
{
    const SIMPLE = 1; //Sadə
    const PREMIUM = 2; //Premium
    const VIP = 3; //VIP
}
