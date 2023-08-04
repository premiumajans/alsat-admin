<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertDescription extends Model
{
    public $timestamps = false;
    public $guarded = [];
    public function advert(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Advert::class);
    }
}
