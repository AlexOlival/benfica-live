<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'email',
        'phone_number',
        'city',
        'country_id',
        'image',
        'thumbnail_image',
        'website',
        'latitude',
        'longitude',
        'is_approved'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function path()
    {
        return '/paises/' . $this->country->slug_pt . '/spots/' . $this->slug;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}