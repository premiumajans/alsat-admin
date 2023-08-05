<?php

namespace Database\Seeders;

use App\Models\Advert;
use App\Models\AdvertDescription;
use App\Models\AdvertPhoto;
use Illuminate\Database\Seeder;

class AdvertSeeder extends Seeder
{
    public function run(): void
    {
        $advert = Advert::create([
            'user_id' => 1,
        ]);
        $description = new AdvertDescription();
        $description->title = 'adas';
        $description->short_description = 'dadsa';
        $description->description = 'adas';
        $description->salary = 3;
        $description->owner = 'sad';
        $description->phone = 444454;
        $advert->description()->save($description);
        $advertPhoto = new AdvertPhoto();
        $advertPhoto->photo = 'photo/example.jpg';
        $advertPhoto->main_photo = 1;
        $advert->photos()->save($advertPhoto);
    }
}
