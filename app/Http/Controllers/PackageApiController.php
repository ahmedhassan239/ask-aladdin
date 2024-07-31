<?php

namespace App\Http\Controllers;

use App\About;
use App\Blog;
use App\Destination;
use App\Excursion;
use App\Option;
use App\Package;
use App\Slider;
use App\Social;
use App\Hotel;
use App\Policy;
use App\Tourtype;
use Illuminate\Http\Request;
use \ClassicO\NovaMediaLibrary\API;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PackageApiController extends Controller
{

/*Single Package*/

    public function gitSinglePackage($dest_id,$id)
    {
        $package = Package::where('id',$id)->with('destination')->orWhere('slug->en',$id)->firstOrFail();
        $dest = Destination::where('id', $dest_id)
        ->orWhere('slug->en', $dest_id)
        ->WhereHas('packages', function ($q) use ($id) {
            $q->where('id', $id)
                ->orWhere('slug->en', $id);
        })->with('packages')
        ->firstOrFail();

        $val = $dest->packages->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });
        $policy = Policy::where('destination_id',$dest->id)->first();

        $tour_type = Tourtype::whereIn('id',json_decode($package->tour_type))->with('destination')->get()

       ->map(function ($x){
            return [
                'destination'=>[
                    'slug'=>$x->destination->slug ?? []
                ],
                'name' => $x->name,
                'slug' => $x->slug,
                'icon' => $x->icon
            ];
        });
        // dd($tour_type );
    
        $data[] =  [
                   'destination'=>[
                       'id'=>$package->destination->id,
                       'slug'=>$package->destination->slug,
                       'name'=>$package->destination->name,
                   ],
                   'category' => [
                    'slug' => 'travel-packages',
                    'name' => 'Travel Packages',
                    ],
                   'id'=>$val->id,
                   'name'=>$val->name,
                   'slug'=> $val->slug,
                   'description'=>$val->description,
                   'overview'=>$val->overview,
                   'discount'=>$val->discount,
                   'tour_types'=> $tour_type,
                   'thumb_alt' => $val->thumb_alt,
                   'thumb' => asset('photos/' . $val->_thumb), 
                   'days' => $val->days,
                   'standard_hotels' => $val->standard_hotel_list ?? [],
                   'comfort_hotels'=>$val->comfort_hotel_list ?? [],
                   'deluxe_hotels'=>$val->deluxe_hotel_list ?? [],
                   'cruise_hotels'=>$val->cruise_hotel_list ?? [],
                   'included' => $val->included_list ?? [],
                   'excluded' => $val->excluded_list ?? [],
                   'highlight' => $val->high_light_list ?? [],
                   'day_data' => $val->day_data_list ?? [],
                   'optional_tours' => $val->optional_tour_list ?? [],
                   'prices' => json_decode($val['prices'])??[],
                   'travel_experiences' => $val->travel_experiences_list ?? [],
                   'banner' => asset('photos/' . $val->_banner),
                   'banner_alt' => $val->alt,
                   'price_policy' => $policy->price_policy ?? "",
                   'payment_policy' => $policy->payment_policy ?? "",
                   'repeated_travellers' => $policy->repeated_travellers ?? "",
                   'travel_schedule' => $policy->travel_schedule ?? "",
                   'image_over_banner'=>asset('photos/' . $val->_image_over_banner),
                   'videos' => $val->videos_list ?? [],
                   'related_packages'=>$val->related_packages_list ?? [],
                   'reviews' =>$val->review_list ?? [],
                   'location_package_map' =>$val->location_package_map ?? '',
                   'tour_type' => json_decode($val->tour_type, true),
                    'start_price' => $val->start_price,
                    'discount' => $val->discount,
                    'number_highlights' => $val->number_highlights,
                    'itinerary' => $val->itinerary ?? [],
                    'meals' => $val->meals,
                    'accommodations' => $val->accommodations,
                    'flights' => $val->flights,
                    'guid_tour' => $val->guide_tour,
                    'hot_offer' => $val->hot_offer == 1,
                    'top_sale' => $val->top_sale == 1,
                    'rate' => $val->rate,
                   'seo'=>[
                       'title' => $val->seo_title,
                       'keywords' => $val->seo_keywords,
                       'robots' => $val->seo_robots,
                       'description' => $val->seo_description,
                       'facebook_description' => $val->facebook_description,
                       'twitter_title' => $val->twitter_title,
                       'twitter_description' => $val->twitter_description,
                       'twitter_image' => $val->twitter_image,
                       'facebook_image' => $val->facebook_image,
                       'facebook_title'=>$val->og_title,
                       'twitter_image'=>asset('photos/' . $val->_twitter_image),
                       'facebook_image'=>asset('photos/' . $val->_facebook_image),
                       'schema'=>$val->seo_schema,
                       
                   ]
                ];
              
        return response()->json([
            'data'=>$data
        ],'200');

    }


/*All packages Showed On Home Page  Apis */

    public function getAllPackages($lang)
    {
       // $data =  cache()->rememberForever('cache_package',  function () use ($lang){

        $query = Package::where([
           ['status','=',1],
           ['featured','=',1]
          ])->with('destination')->get();
        $packages = $query->map(function ($val){
            return [
                'destination'=>[
                    'slug'=>$val->destination->slug,
                ],
                'id'=>$val->id,
                'name'=>$val->name,
                'slug'=> $val->slug,
                'description'=>$val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'start_price' => $val->start_price,
                'days' => $val->days,
            ];

        });
        return response()->json([
            'data'=>[
                'packages'=>$packages,
            ]
        ]);
    }

    public function getMultiCountryPackages($lang)
    {
       // $data =  cache()->rememberForever('cache_package',  function () use ($lang){

        $query = Package::where([
           ['status','=',1],
           ['multi','=',1]
          ])->with('destination')->get();
        $packages = $query->map(function ($val){
            return [
                'destination'=>[
                    'slug'=>$val->destination->slug,
                ],
                'id'=>$val->id,
                'name'=>$val->name,
                'slug'=> $val->slug,
                'description'=>$val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'start_price' => $val->start_price,
                'days' => $val->days,
                'hot_offer' => $val->hot_offer,
            ];

        });
        return response()->json([
            'data'=>[
                'packages'=>$packages,
            ]
        ]);
    }


}
