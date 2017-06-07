<?php

namespace Couchcat;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	protected $fillable = ['name', 'contact_name', 'contact_email'];
	
    public function licenses()
    {
    	$this->hasMany('Couchcat\License');
    }
}
