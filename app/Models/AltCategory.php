<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;


class AltCategory extends Model implements TranslatableContract
{
    use LogsActivity, Translatable;
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function advert(): void
    {
        $this->hasMany(Advert::class);
    }
    public $timestamps = false;
    public array $translatedAttributes = ['name'];
    protected $fillable = ['status'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['status']);
    }
}
