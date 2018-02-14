<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['name', 'contact_name', 'contact_email'];
    
    public function licenses()
    {
        $this->hasMany(\App\License::class);
    }
}
