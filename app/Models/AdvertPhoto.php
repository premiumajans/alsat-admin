<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AdvertPhoto extends Model
{
    public function advert(): void
    {
        $this->belongsTo(Advert::class);
    }
    protected $guarded = [];
    public $timestamps = false;
}
