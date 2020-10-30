<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'meta_title',
        'meta_description',
        'image',
    ];

    // public function getImageAttribute($value)
    // {
    //     return env('APP_URL') . '/storage' . '/' . $value;
    // }
}
