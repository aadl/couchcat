<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'contact_name', 'contact_email'];

    public function licenses()
    {
        return $this->hasMany('App\License');
    }
}
