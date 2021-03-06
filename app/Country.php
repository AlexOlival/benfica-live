<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function spots()
    {
        return $this->hasMany(Spot::class);
    }

    public function getRouteKeyName()
    {
        return 'slug_pt';
    }
}
