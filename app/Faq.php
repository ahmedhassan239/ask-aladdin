<?php

namespace App;

use App\Traits\GlobalAttributes;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    public $table = "faqs";
    use IsTranslatable;

    public $translatable = ['name','slug','description','overview','thumb_alt','alt','seo_title','seo_keywords','seo_robots','seo_description','facebook_description','twitter_title',
        'twitter_description','travel_experiences','category_name',
        'destination_name','destination_slug',
        'destination_description','package_description',
        'destination_alt','package_thumb_alt',
        'category_name',
        'destination_seo_title','destination_seo_keywords','destination_seo_robots',
        'destination_seo_robots','destination_seo_description','destination_facebook_description',
        'destination_twitter_title','destination_twitter_description','content','destination_banner_alt','og_title'
        
    ];

    protected $casts = [
        'faq' => 'array',
        'created_at'=>'datetime:M Y'
        ];
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }//END OF categories

    public function getFaqListAttribute(){
        $faq_data = array();
        if (is_array($this->faq)) {
            foreach ($this->faq as $row) {
                $faq_data[] = [
                    'myth'=>$row['attributes']['myth'][app()->getLocale()],
                    'fact'=> $row['attributes']['fact'][app()->getLocale()],
                    'overview' => $row['attributes']['overview'][app()->getLocale()],
                    'image' => asset('photos/' . $row['attributes']['new_image']),
                    
                ];
            }
            return $faq_data;
        }
    }
}
