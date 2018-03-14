<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public function getExpiredAttribute()
    {
        return Carbon::parse($this->expires)->isPast();
    }
}
