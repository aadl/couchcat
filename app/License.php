<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'vendor_id', 'cost', 'notes', 'license_slug', 'starts', 'expires', 'patrons_only' ];

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public function getExpiredAttribute()
    {
        return Carbon::parse($this->expires)->isPast();
    }

    public function getRecordsCountAttribute()
    {
        $couch = resolve('Couchdb');
        $records_view = $couch->key($this->license_slug)->group(true)->getView('couchcat', 'licensed_from');
        return $records_view->rows[0]->value ?? 0;
    }
}
