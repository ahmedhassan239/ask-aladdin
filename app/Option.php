<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $table = "options";

    public function package()
    {
        return $this->belongsTo(Package::class);
//        return $this->hasMany(Package::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination
}
