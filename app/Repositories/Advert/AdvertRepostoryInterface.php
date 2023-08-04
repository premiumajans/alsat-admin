<?php
namespace App\Repositories\Advert;

use App\Models\Advert;
use Illuminate\Http\Request;

interface AdvertRepostoryInterface
{
    public function createAdvert(Request $request);
    public function getAdverts();
    public function getVipAdverts();
    public function getLastAdverts();
    public function getPremiumAdverts();
    public function getAdvertsById($id);
    public function updateAdvert(Request $request, $id);
    public function deleteAdvertById($id);
}
