<?php

namespace App\Services;

use App\Http\Enums\AdvertEnum;
use App\Models\Advert;
use App\Models\PremiumAdvert;
use Carbon\Carbon;
use Exception;

class PremiumAdvertService
{
    public function cleanUpExpiredPremiumVacancies(): void
    {
        $adverts = $this->getAdvertsWithPremium();
        foreach ($adverts as $advert) {
            $this->handleExpiredPremium($advert);
        }
    }

    private function getAdvertsWithPremium(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Advert::with('premium')->get();
    }

    private function handleExpiredPremium(Advert $advert): void
    {
        if ($advert->premium()->exists() && $this->isPremiumExpired($advert)) {
            $this->deActive($advert);
        }
    }

    public function isPremiumExpired(Advert $advert): bool
    {
        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $advert->premium->end_time);
        return $endTime->lt(Carbon::now());
    }

    public function deActive(Advert $advert): void
    {
        $advert->premium->status = 0;
        $advert->premium->save();
    }

    public function makeAdvertPremium($id, int $durationInMonths): void
    {
        try {
            $advert = Advert::find($id);
            $premium = new PremiumAdvert();
            $premium->premium = AdvertEnum::PREMIUM;
            $premium->start_time = Carbon::now();
            $premium->end_time = Carbon::now()->addMonths($durationInMonths);
            $advert->premium()->save($premium);
            alert()->success(__('messages.success'));
        } catch (Exception $e) {
            alert()->error(__('messages.error'));
        }
    }

    public function extendPremiumTime($id, int $durationInDays): void
    {
        try {
            $advert = Advert::find($id);
            $premium = $advert->premium;
            if ($premium) {
                $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $premium->end_time);
                $newEndTime = $endTime->copy()->addDays($durationInDays);
                $premium->end_time = $newEndTime;
                $premium->save();
                alert()->success(__('messages.success'));
            } else {
                alert()->error(__('messages.error'));
            }
        } catch (Exception $e) {
            alert()->error(__('messages.error'));
        }
    }
}
