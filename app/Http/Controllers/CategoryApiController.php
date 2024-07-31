<?php

namespace App\Http\Controllers;

use App\Category;
use App\Cruise;
use App\Destination;
use App\Excursion;
use App\Package;
use ClassicO\NovaMediaLibrary\API;


class CategoryApiController extends Controller
{
    public function getSingleDestinationCategories($dest_id, $lang)
    {

        $query = Destination::with(['categories' => function ($x) {
            $x->where('status', 1)->where('showed', 1);
        }])->where('id', $dest_id)->orWhere('slug->en', $dest_id)->get();


        $data = $query->map(function ($val) {
            return [
                'categories' => $val['categories']->map(function ($value) use ($val) {
                    return [
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'title' => $value->title,
                        'description' => $value->seo_description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                }),
                'destination' => [
                    'banner' =>asset('photos/' . $val->_banner),
                    'slug' => $val->slug,
                ],
                
            ];
        });
        //+
        return response()->json([
            'data' => $data
        ], '200');
    }



    public function destinationHotOffer($dest_id, $lang)
    {
        $packages = Package::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })
            ->where('status', '=', 1)
            ->where('hot_offer', '=', 1)

            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' =>asset('photos/' . $value->_thumb)
                ];
            });

        $excursions = Excursion::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })
            ->where('status', 1)
            ->where('hot_offer', 1)

            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'city' => [
                        'slug' => $value->city->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });

        $cruises = Cruise::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })
            ->where('status', 1)
            ->where('hot_offer', 1)
            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });

        $data []= [

            'packages' => $packages ?? [],
            'excursions' => $excursions ?? [],
            'cruises' => $cruises ?? [],
        ];

        return response()->json([
            'data' => $data
        ], '200');
    }

    public function singleDestinatopnCategory($dest_id, $id)
    {
        $query = Category::whereHas('destination', function ($x) use ($dest_id) {
            $x->where('id', $dest_id)->orWhere('slug->en', $dest_id);
        })->where(function ($query) use ($id) {
            $query->where('id', $id)
                ->orWhere('slug->en', $id);
        })->where('status',1)->get();
        $data = $query->map(function ($val) {
            return [
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'banner_alt' => $val->alt,
                'banner' =>asset('photos/' . $val->_banner),
                'seo' => [
                    'title' => $val->seo_title,
                    'keywords' => $val->seo_keywords,
                    'robots' => $val->seo_robots,
                    'description' => $val->seo_description,
                    'facebook_description' => $val->facebook_description,
                    'twitter_title' => $val->twitter_title,
                    'twitter_description' => $val->twitter_description,
                    'facebook_title' => $val->og_title,
                    'twitter_image' => asset('photos/' . $val->_twitter_image),
                    'facebook_image' =>asset('photos/' . $val->_facebook_image),
                    'schema'=>$val->seo_schema,

                ]
            ];
        });

        return response()->json([
            'data' => $data
        ], '200');
    }
}
