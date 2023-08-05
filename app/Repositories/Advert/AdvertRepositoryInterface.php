<?php

namespace App\Repositories\Advert;

use App\Models\Advert;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

interface AdvertRepositoryInterface
{
    public function getAll();

    public function getAdverts();

    public function getLastAdverts();

    public function getAdvertsById($id);


    public function getVipAdverts();

    public function makeVipAdvert(int|string $id);

    public function getPremiumAdverts();

    public function makePremiumAdvert(int|string $id);

    public function createAdvert(Request $request, $owner, $description = true);

    public function createAdvertDescription(Request $request, $advert);

    public function updateAdvert(Request $request, $id);

    public function deleteAdvertById($id);
}
