<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'index_pages',
        'google_analytics',
        'facebook_ads',
    ];

    // public function getLogoAttribute($value)
    // {
    //     return env('APP_URL') . '/storage' . '/' . $value;
    // }
}
