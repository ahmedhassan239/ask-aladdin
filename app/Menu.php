<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination
    protected $casts =[
        'created_at'=>'datetime:M Y',
        'pages_list'=> 'array',
        'packages_list'=>'array',
        'blogs_list'=> 'array',
        'cruises_list'=> 'array',
        'excursions_list'=> 'array',
        'travel_guides_list'=> 'array',
        'categories_list'=> 'array',
        'hotels_list'=> 'array', 
        'tour_type_list'=> 'array', 
    ];
    public function getPagesListAttribute(){
        if ($this->pages != Null) {
            $pages = Page::whereIn('id', json_decode($this->pages,true))->with('category','destination')->get()
            // dd($related_pages);
                ->map(function ($value) {
                return [
                    'category'=>[
                        'slug'=>$value->category->slug ?? []
                    ],
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'description' => $value->seo_description,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb),
                ];
            });
            return $pages;
        }
    }
    public function getPackagesListAttribute(){
        if ($this->packages != Null) {
            return Package::whereIn('id',json_decode($this->packages))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'destination'=>[
                            'name'=>$value->destination->name ?? [],
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'days'=>$value->days,
                        'start_price'=>$value->start_price,
                        'rate'=>$value->rate,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                        'hot_offer' => $value->hot_offer,
                        'top_sale' => $value->hot_offer,
                    ];
                });
        }

    }
    public function getBlogsListAttribute(){
        if ($this->blogs  != Null) {
            $blogs  = Blog::whereIn('id', json_decode($this->blogs ,true))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'thumb_alt' => $value->thumb_alt,
                        // 'thumb' =>asset('photos/' . $value->_thumb), //API::getFiles($value->thumb)
                        'thumb' => asset('photos/' . $value->_thumb),//API::getFiles($value->thumb)
                    ];
                });
            return $blogs;
        }
    }
    public function getTourTypeListAttribute(){
        if ($this->tour_type  != Null) {
            $tour_type  = Tourtype::whereIn('id', json_decode($this->tour_type ,true))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb)
                    ];
                });
            return $tour_type;
        }
    }

    public function getCruisesListAttribute(){
        if ($this->cruises  != Null) {
            $cruises  = Cruise::whereIn('id', json_decode($this->cruises  ,true))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                });
            return $cruises;
        }
    }
    public function getExcursionsListAttribute(){
        if ($this->excursions  != Null) {
            $excursions  = City::whereIn('id', json_decode($this->excursions ,true))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->seo_description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb)
                    ];
                });
            return $excursions;
        }
    }
    public function getTravelGuidesListAttribute(){
        if ($this->travel_guides != Null) {
            $travel_guides = TravelGuide::whereIn('id',json_decode($this->travel_guides))
            ->with('destination')->get()->map(function ($value) {
                return [
                    'destination'=>[
                        'name'=>$value->destination->name ?? [],
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'description' => $value->description,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb), //API::getFiles($value->thumb)
                ];
            });
           return $travel_guides;
        }
    }
    public function getCategoriesListAttribute()
    {
        if ($this->categories  != Null) {
            $categories  = Category::whereIn('id', json_decode($this->categories, true))->with('destination')->get()
                // dd($related_pages);
                ->map(function ($value) {
                    return [
                        'destination' => [
                            'slug' => $value->destination->slug ?? [],
                            'name' => $value->destination->name ?? [],
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->seo_description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                });
            return $categories;
        }
    }
    public function getHotelsListAttribute()
    {
        if ($this->hotels  != Null) {
            $hotels  = Hotel::whereIn('id', json_decode($this->hotels, true))->with('destination')->get()
                // dd($related_pages);
                ->map(function ($value) {
                    return [
                        'destination' => [
                            'slug' => $value->destination->slug ?? [],
                            'name' => $value->destination->name ?? [],
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->seo_description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                });
            return $hotels;
        }
    }

    public function getLinksListAttribute()
    {
        $linksArray = json_decode($this->links, true);

        $links = array();

        if (is_array($linksArray)) {
            foreach ($linksArray as $key) {
                $links[] = [
                    'title' => $key['attributes']['title'][app()->getLocale()],
                    'link' => $key['attributes']['link'][app()->getLocale()],
                    'thumb' => asset('photos/' . $key['attributes']['thumb']),
                ];
            }
        }

        return $links;
    }


}
