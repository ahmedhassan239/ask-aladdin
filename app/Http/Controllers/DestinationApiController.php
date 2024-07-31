<?php

namespace App\Http\Controllers;

use App\Blog;
use App\City;
use App\Cruise;
use App\Destination;
use App\Excursion;
use App\Faq;
use App\Package;
use App\TravelGuide;
use Carbon\Carbon;
use ClassicO\NovaMediaLibrary\API;

class DestinationApiController extends Controller
{

    /* return json data */
    private function mapingData($map)
    {
        $package = $map->map(function ($val) {
            return [

                'id' => $val->id,
                'slug' => $val->slug,
                'name' => $val->name,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' =>asset('photos/' . $val->_thumb) ,
                'location_map' => $val->location_package_map,
                'duration_in_days' => $val->days,
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
            ];
        });
        $seo = $this->getSeoCollection($map);
        $destination = $map->map(function ($val) {
            return [

                'id' => $val['destination']['id'],
                'slug' => $val['destination']['slug'],
                'name' => $val['destination']['name'],
                'description' => $val['destination']['description'],
                'alt' => $val['destination']['alt'],
                'banner' =>asset('photos/' . $val['destination']['_banner']),
            ];
        })->unique();
        return response()->json([
            'data' => [
                'destination' => $destination,
                'package' => $package,
                'seo' => $seo,
            ]
        ], '200');
    }


    /*Single Destination Apis*/

    public function getSingleDestinations($id, $lang)
    {
        $destination = Destination::where('id', $id)->orWhere('slug->en', $id)
            ->where('status', 1)->get()->map(function ($val) {
                return [
                    'id' => $val->id,
                    'name' => $val->name,
                    'slug' => $val->slug,
                    'description' => $val->description,
                    // 'icon' => API::getFiles($val->icon),
                    'thumb_alt' => $val->thumb_alt,
                    'thumb' => asset('photos/' . $val->_thumb),
                    'banner' =>asset('photos/' . $val->_banner),
                    'banner_alt' => $val->alt,
                    'related_pages' => $val->related_pages_list ?? [],
                    'seo' => [
                        'title' => $val->seo_title,
                        'keywords' => $val->seo_keywords,
                        'robots' => $val->seo_robots,
                        'description' => $val->seo_description,
                        'facebook_description' => $val->facebook_description,
                        'twitter_title' => $val->twitter_title,
                        'twitter_description' => $val->twitter_description,
                        'facebook_title' => $val->og_title,
                        'schema'=>$val->seo_schema,
                        'twitter_image' =>asset('photos/' . $val->_twitter_image),
                        'facebook_image' =>asset('photos/' . $val->_facebook_image) ,
                    ]
                ];
            });

        return response()->json([
            'data' => $destination
        ], '200');
    }

    /*All Destinations Apis*/
    public function getDestinations($lang)
    {

        $destinations = Destination::where('status', 1)->where('featured', 1)->get()
            ->map(function ($val) {
                return [
                    'id' => $val->id,
                    'name' => $val->name,
                    'slug' => $val->slug,
                    'description' => $val->description,
                    'thumb_alt' => $val->thumb_alt,
                    'thumb' => asset('photos/' . $val->_thumb),
                ];
            });



        return response()->json([
            'data' => $destinations
        ]);
    }

    /*Single Destination Api  it will contain all packages related to this destination */

    public function getSingleDestinationPackages($id, $lang)
    {

        $packages = Package::with('destination')
            ->whereHas('destination', function ($query) use ($id) {
                return $query->where('id', $id)->orWhere('slug->en', $id)->where('status', 1);
            })->where('status', 1)->get();

        return $this->mapingData($packages);
    }

    /*Single Destination Api  it will contain all packages related to this destination Where Category Type */

    public function getSingleDestinationPackagesWhereCategory($id, $cat_id, $lang)
    {
        $packages =  Package::with('destination')
            ->whereHas('destination', function ($query) use ($id) {
                return $query->where('id', $id)->orWhere('slug->en', $id)->where('status', 1);
            })->where('category', 'like', '%' . $cat_id . "%")->get();

        return $this->mapingData($packages);
    }

    /*Single Destination Api  it will contain all Excursions related to this destination */

    public function getSingleDestinationExcursions($id, $lang)
    {

        $query =  Excursion::with('destination', 'destination.city')
            ->whereHas('destination', function ($x) use ($id) {
                return $x->where('id', $id)->orWhere('slug->en', $id)->where('status', 1);
            })->where('status', 1)->get();
        $excursions = $query->map(function ($val) {
            return [
                'id' => $val->id,
                'slug' => $val->slug,
                'name' => $val->name,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'start_price' => $val->price_11,
                'location_map' => $val->map_url,
                'duration' => $val->duration,
                'travellers' => $val->travellers,


                //                'reviews'=>$reviews,
                'rate' => $val->rate,
                'meals' => $val->meals,
                'services_number' => $val->services_no,
                'activities_number' => $val->activities_no,
                'guide_tour' => $val->guide_tour,
                'discount' => $val->discount,
                'best_sale' => $val->top_sale == 1,
                'hot_offer' => $val->hot_offer == 1,
                'cities' => $val['destination']['city']->map(function ($val) {
                    return [
                        'id' => $val->id,
                        'name' => $val->name,
                    ];
                }),
            ];
        });
        $destination = $query->map(function ($val) {
            return [
                'id' => $val->destination_id,
                'slug' => $val['destination']['slug'],
                'name' => $val['destination']['name'],
                'description' => $val['destination']['description'],
                'alt' => $val['destination']['alt'],
                'banner' => asset('photos/' . $val['destination']['_banner']),
                
            ];
        })->unique();
        //        $cities = $query->map(function ($val){
        //                $val['destination']['city']->map(function ($value){
        //
        //            return [
        //                'id'=>$value->id,
        //                'name'=>$value->name,
        //                ];
        //            });
        //        });
        $seo = $this->getSeoCollection($query);
        return response()->json([
            'data' => [
                'destination' => $destination,
                //                'cities'=> $cities,
                'excursions' => $excursions,
                'seo' => $seo,
            ],
        ], '200');
    }

    /*Single Destination Api  it will contain all Blogs related to this destination */

    public function getSingleDestinationBlogs($id, $lang)
    {
        $query = Blog::with('destination')
            ->whereHas('destination', function ($x) use ($id) {
                return $x->where('id', $id)->orWhere('slug->en', $id)->where('status', 1);
            })->where('status', 1)->paginate(9);

        // dd($query);


        $blogs =   $query->map(function ($val) {
            return [
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),//API::getFiles($val->thumb),
                'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->isoFormat('MMM Do YY'),
            ];
        });
        $destination =  $query->map(function ($val) {
            return [
                'id' => $val->destination_id,
                'name' => $val['destination']['name'],
                'description' => $val['destination']['description'],
                'slug' => $val['destination']['slug'],
                'banner' => asset('photos/' . $val['destination']['_banner']),
                'banner_alt' => $val['destination']['alt'],
            ];
        })->unique();
        $seo = $this->getCollection($query);
        return response()->json([
            'paginator' => [
                'perPage' => $query->perPage(),
                'currentPage' => $query->currentPage(),
                'total' => $query->total(),
                'lastPage' => $query->lastPage(),
            ],
            'data' => [
                'category' => [
                    'slug' => 'travel-blogs',
                    'name' => 'Travel Blogs',
                ],
                'destination' => $destination,
                'blogs' => $blogs,
                'seo' => $seo,
            ]
        ], '200');
    }


    /*Single Destination Api  it will contain all Faqs related to this destination */
    public function getSingleDestinationFaqs($id)
    {

        $query = Faq::with(['destination', 'category'])
            ->whereHas('destination', function ($x) use ($id) {
                return $x->where('id', $id)->orWhere('slug->en', $id)->where('status', 1);
            })->where('status', 1)->get();
        $faqs = $query->map(function ($val) {

            return [
                //                    'category_name'=>$val['category']['name'],
                'faqs' => $val->faq_list,
                'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->isoFormat('MMM Do YY'),
            ];
        });

        $destination = $query->map(function ($val) {

            return [
                'id' => $val->destination_id,
                'name' => $val['destination']['name'],
                'description' => $val['destination']['description'],
                'banner' => asset('photos/' . $val['destination']['_banner']),
                'slug' => $val['destination']['slug'],
            ];
        })->unique();
        $seo = $this->getCollection($query);
        return response()->json([
            'data' => [
                'category' => [
                    'slug' => 'myths-facts',
                    'name' => 'Myths & Facts',
                ],
                'destination' => $destination,
                'faqs' => $faqs,
                'seo' => $seo,
            ]
        ], '200');
    }


    /*Single Destination Api  it will contain all Cruises related to this destination */

    public function getSingleDestinationCruises($dest_id, $lang)
    {

        $query = Cruise::with('destination')
            ->whereHas('destination', function ($x) use ($dest_id) {
                return $x->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
            })->where('status', 1)->get();
        $cruises =  $query->map(function ($val) {
            return [
                'id' => $val->id,
                'slug' => $val->slug,
                'name' => $val->name,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'start_price' => $val->double_room_price,
                'duration_in_days' => $val->days,
                'rate' => $val->rate,
                'location' => $val->location,
                //                'reviews'=>$reviews,
                'itinerary' => $val->itinerary,
                'meals' => $val->meals,
                'accommodations' => $val->accommodations,
                'services_number' => $val->services_no,
                'activities_number' => $val->activities_no,
                'guide_tour' => $val->guide_tour,
                'discount' => $val->discount,
                'best_sale' => $val->top_sale == 1,
                'hot_offer' => $val->hot_offer == 1
            ];
        });
        $destination =  $query->map(function ($val) {
            return [
                'id' => $val['destination']['id'],
                'slug' => $val['destination']['slug'],
                'name' => $val['destination']['name'],
                'description' => $val['destination']['description'],
                'alt' => $val['destination']['alt'],
                'banner' => asset('photos/' . $val['destination']['_banner']),
            ];
        })->unique();
        $seo = $this->getCollection($query);
        return response()->json([
            'data' => [
                'category' => [
                    'slug' => 'travel-cruises',
                    'name' => 'Travel Cruises',
                ],
                'destination' => $destination,
                'cruises' => $cruises,
                'seo' => $seo,
            ]
        ], '200');
    }


    /*Single Destination Api  it will contain all Travel Guides related to this destination */

    public function getSingleDestinationTravelGuides($dest_id, $lang)
    {
        $query = TravelGuide::with('destination')
            ->whereHas('destination', function ($x) use ($dest_id) {
                return $x->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
            })->where('status', 1)->get();
        $travelGuides = $query->map(function ($val) {
            return [
                'id' => $val->id,
                'slug' => $val->slug,
                'name' => $val->name,
                'description' => $val->description,
                'thumb' => asset('photos/' . $val->_thumb),
                'thumb_alt' => $val->thumb_alt,
            ];
        });
        $destination = $query->map(function ($val) {
            return [
                'id' => $val['destination']['id'],
                'name' => $val['destination']['name'],
                'slug' => $val['destination']['slug'],
                'description' => $val['destination']['description'],
                'banner' => asset('photos/' . $val['destination']['_banner']),
                'alt' => $val['destination']['alt'],
            ];
        })->unique();

        $seo = $this->getCollection($query);

        return response()->json([
            'data' => [
                'category' => [
                    'slug' => 'travel-guides',
                    'name' => 'Travel Guides',
                ],
                'destination' => $destination,
                'travelGuides' => $travelGuides,
                'seo' => $seo,
            ]
        ], '200');
    }

    public function getSeoCollection($query)
    {
        $seo = $this->getCollection($query);
        return $seo;
    }

    public function getCollection($query)
    {
        $seo = $query->map(function ($val) {
            return [
                'title' => $val['destination']['seo_title'],
                'keywords' => $val['destination']['seo_keywords'],
                'robots' => $val['destination']['seo_robots'],
                'description' => $val['destination']['seo_description'],
                'facebook_description' => $val['destination']['facebook_description'],
                'twitter_title' => $val['destination']['twitter_title'],
                'twitter_description' => $val['destination']['twitter_description'],
                'twitter_description' => $val['destination']['og_title'],
                'twitter_description' => $val['destination']['seo_schema'],

                'twitter_image' => asset('photos/' . $val['destination']['_twitter_image']),
                'facebook_image' => asset('photos/' . $val['destination']['_facebook_image'])
            ];
        })->unique();
        return $seo;
    }
}
