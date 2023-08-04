<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VipAdvert extends Model
{
    protected $guarded = [];
    public function advert(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Advert::class);
    }
}
