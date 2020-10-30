<?php

namespace App\Models;

use App\Notifications\NotifyContact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactPage extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'meta_title',
        'meta_description',
        'image',
    ];

    // public function getImageAttribute($value)
    // {
    //     return env('APP_URL') . '/storage' . '/' . $value;
    // }

    public function sendNotificationEmail($name, $mail, $message)
    {
        $this->notify(new NotifyContact($name, $mail, $message));
    }
}
