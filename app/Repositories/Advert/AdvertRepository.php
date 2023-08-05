<?php

namespace App\Repositories\Advert;

use App\Http\Enums\AdvertEnum;
use App\Http\Helpers\CRUDHelper;
use App\Models\Advert;
use App\Models\AdvertDescription;
use App\Models\PremiumAdvert;
use App\Models\User;
use App\Models\VipAdvert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdvertRepository implements AdvertRepositoryInterface
{

    public function getAll()
    {

    }

    public function getAdverts(): Collection
    {
        return $this->getValidAdverts();
    }

    public function getLastAdverts(): array
    {
        return $this->getValidAdverts(9);
    }

    public function createAdvert(Request $request, $owner, $description = true): string
    {
        $advert = $this->createBaseAdvert($owner);
        if ($description) {
            $this->createAdvertDescription($request, $advert);
        }
        return 'advert-add-successfully';
    }

    public function createAdvertDescription(Request $request, $advert): void
    {
        $advertDescription = new AdvertDescription();
        $advertDescription->title = $request->title;
        $advertDescription->short_description = $request->short_description;
        $advertDescription->description = $request->description;
        $advertDescription->salary = $request->salary;
        $advertDescription->owner = $request->owner;
        $advertDescription->phone = $request->phone;
        $advert->description()->save($advertDescription);
    }

    public function getVipAdverts(): Collection
    {
        return $this->getValidVipOrPremiumAdverts(VipAdvert::class);
    }

    public function getPremiumAdverts(): Collection
    {
        return $this->getValidVipOrPremiumAdverts(PremiumAdvert::class);
    }

    public function makeVipAdvert(int|string $id): string
    {
        return $this->makePremiumOrVipAdvert($id, VipAdvert::class);
    }

    public function makePremiumAdvert(int|string $id): string
    {
        return $this->makePremiumOrVipAdvert($id, PremiumAdvert::class);
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
        $advert = Advert::find($id);
        $this->deleteAdvertPhotos($advert);
        $advert->delete();
        return null;
    }
    private function createBaseAdvert($owner): Advert
    {
        $advert = new Advert();
        $owner['user']->adverts()->save($advert);
        return $advert;
    }
    private function getValidAdverts($limit = null): Collection
    {
        $query = Advert::where('end_time', '>', Carbon::now())
            ->where('admin_status', '=', 1)
            ->with('photos', 'description');
        if ($limit !== null) {
            $query->limit($limit);
        }
        return $query->get();
    }

    private function makePremiumOrVipAdvert(int|string $id, string $type): string
    {
        $advert = Advert::find($id);
        if (!$advert->{$type}()->exists()) {
            $adType = new $type();
            $adType->{$type} = AdvertEnum::PREMIUM; // or Vip
            $adType->start_time = Carbon::now();
            $adType->end_time = Carbon::now()->addMonths(1);
            $advert->{$type}()->save($adType);
            return 'advert-change-to-' . strtolower($type);
        } else {
            return 'advert-is-already-' . strtolower($type);
        }
    }
    private function getValidVipOrPremiumAdverts(string $type): Collection
    {
        $statusColumn = ($type === PremiumAdvert::class) ? 'premium_status' : 'vip_status';
        return Advert::where($statusColumn, '=', 1)
            ->where('end_time', '>', Carbon::now())
            ->where('admin_status', '=', 1)
            ->whereHas($type, function ($query) {
                $query->where('start_time', '<', Carbon::now())
                    ->where('end_time', '>', Carbon::now());
            })
            ->with('photos', 'description')
            ->get();
    }
    private function deleteAdvertPhotos(Advert $advert): void
    {
        if ($advert->photos()->exists()) {
            foreach ($advert->photos as $advertPhoto) {
                if (file_exists($advertPhoto)) {
                    unlink(public_path($advertPhoto));
                }
            }
        }
    }
}
