<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'vendor_id', 'cost', 'notes', 'statistics_stub', 'starts', 'expires' ];

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public function getExpiredAttribute()
    {
        return Carbon::parse($this->expires)->isPast();
    }
}
