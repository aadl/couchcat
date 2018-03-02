<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['name', 'contact_name', 'contact_email'];
    
    public function licenses()
    {
        return $this->hasMany('App\License');
    }
}
