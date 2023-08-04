<?php

namespace App\Repositories\Advert;

use App\Models\Advert;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvertRepostory implements AdvertRepostoryInterface
{

    public function getAdverts()
    {
        return Advert::where('end_time','<',Carbon::now())->get();
    }
    public function createAdvert(Request $request)
    {
        // TODO: Implement createAdvert() method.
    }
    public function getVipAdverts()
    {
        // TODO: Implement getVipAdverts() method.
    }
    public function getLastAdverts()
    {
//        return
    }
    public function getPremiumAdverts()
    {
        // TODO: Implement getPremiumAdverts() method.
    }
    public function getAdvertsById($id)
    {
        // TODO: Implement getAdvertsById() method.
    }
    public function updateAdvert(Request $request, $id)
    {
        // TODO: Implement updateAdvert() method.
    }
    public function deleteAdvertById($id)
    {
        // TODO: Implement deleteAdvertById() method.
    }
}
