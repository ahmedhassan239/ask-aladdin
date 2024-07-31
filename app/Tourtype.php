<?php

namespace App;

use App\Traits\IsTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tourtype extends Model
{
    use HasFactory;
    use IsTranslatable;
    public $table = "tour_types";
    public $translatable = ['name','slug','overview','description','thumb_alt','alt','banner_alt','seo_title',
        'seo_keywords','seo_robots','seo_description','facebook_description','twitter_title',
        'twitter_description','og_title'];
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination

    

    

}
