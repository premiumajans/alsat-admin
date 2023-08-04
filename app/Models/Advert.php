<?php

namespace App\Models;

use App\Http\Enums\AdvertStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    protected $guarded = [];

    public function category(): void
    {
        $this->belongsToMany(AltCategory::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function description(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AdvertDescription::class);
    }

    public function photos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdvertPhoto::class);
    }

    public function premium(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PremiumAdvert::class);
    }
    public function vip(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VipAdvert::class);
    }

    public function feedback(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function scopeStatus($query, $status, $user = null)
    {
        $query = $query->where('advert_status', $status)->with('description');
        if ($user) {
            $query = $query->where('user_id', $user);
        }
        return $query->get();
    }

    public function scopeActive($query, $user = null)
    {
        return $query->status(AdvertStatusEnum::ACCEPTED, $user);
    }

    public function scopePending($query, $user = null)
    {
        return $query->status(AdvertStatusEnum::PENDING, $user);
    }

    public function scopeDeclined($query, $user = null)
    {
        return $query->status(AdvertStatusEnum::DECLINED, $user);
    }

    public function scopeDeactive($query, $user = null)
    {
        return $query->status(AdvertStatusEnum::DEACTIVE, $user);
    }

    public static function countByStatus($status)
    {
        return self::where('advert_status', $status)->count();
    }

    public static function countAccepted($user = null)
    {
        return self::status(AdvertStatusEnum::ACCEPTED, $user)->count();
    }

    public static function countDeclined($user = null)
    {
        return self::status(AdvertStatusEnum::DECLINED, $user)->count();
    }

    public static function countPending($user = null)
    {
        return self::status(AdvertStatusEnum::PENDING, $user)->count();
    }

    public static function countDeactivated($user = null)
    {
        return self::status(AdvertStatusEnum::DEACTIVE, $user)->count();
    }
}
