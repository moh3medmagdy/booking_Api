<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{

      use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'location',
        'date',
        'avalible_seats',
        'category_id',
    ];

    public function category(){
        return $this->belongsTo(Category::class);          //hasOne
    }

    public function booking(){
        return $this->hasMany(Booking::class);
    }
}
