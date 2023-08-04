<?php
namespace App\Http\Enums;
use Illuminate\Validation\Rules\Enum;
class AdvertStatusEnum extends Enum
{
    const PENDING = 1;  //Gözləmədə olan elanlar
    const DECLINED = 2; //Qəbul olunmamış elanlar
    const ACCEPTED = 3; //Qəbul olunmus elanlar
    const DEACTIVE = 4; //Deaktiv olunmus elanlar
}
