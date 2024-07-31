<?php

namespace App\Http\Controllers;

use App\Destination;
use App\Package;
use App\Tourtype;
use Illuminate\Http\Request;

class TourtypeApiController extends Controller
{

    public function tourTypeslist($dest_id)
    {
        $destination = Destination::where('id', $dest_id)->orWhere('slug->en', $dest_id)->first();

        $query = Tourtype::where('destination_id', $destination->id)->orWhere('slug->en', $destination->slug)->get();

        $list = $query->map(function ($val) {
            return [
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'icon' => $val->icon,
            ];
        });
        return response()->json([
            'data' => $list
        ]);
    }

    public function getSingleTourType($dest_id, $id)
    {
        $destination = Destination::where('id', $dest_id)
            ->orWhere('slug->en', $dest_id)
            ->first();

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        $tourType = Tourtype::where(function ($q) use ($destination) {
            $q->where('destination_id', $destination->id)
                ->orWhere('slug->en', $destination->slug);
        })
            ->where(function ($q) use ($id) {
                $q->where('id', $id)
                    ->orWhere('slug->en', $id);
            })
            ->first();


        if (!$tourType) {
            return response()->json(['message' => 'Tour type not found'], 404);
        }

        // Retrieve all packages where the tour_type JSON field contains the tourType id
        $tt = Package::where(function ($query) use ($destination) {
            $query->where('destination_id', $destination->id)
                ->orWhere('slug->en', $destination->slug);
        })
            ->whereNotNull('tour_type')
            ->where('tour_type', '<>', '[]')  // to make sure the JSON array is not empty
            ->get();

        $filtered = $tt->filter(function ($value,  $key) use ($tourType) {

            return in_array($tourType->id, json_decode($value->tour_type));
        });

        $packages = $filtered->map(function ($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'slug' => $value->slug,
                'description' => $value->description,
                'days' => $value->days,
                'start_price' => $value->start_price,
                'rate' => $value->rate,
                'thumb_alt' => $value->thumb_alt,
                'thumb' => asset('photos/' . $value->_thumb),
                'destination' => [
                    'id' => $value->destination->id,
                    'name' => $value->destination->name,
                    'slug' => $value->destination->slug,
                ]
            ];
        })->values();

        // Extract all tour_type values from the collection

        // Constructing the response
        $response = [
            'destination' => [
                'id' => $destination->id,
                'slug' => $destination->slug,
                'name' => $destination->name,
            ],
            'id' => $tourType->id,
            'name' => $tourType->name,
            'slug' => $tourType->slug,
            'icon' => $tourType->icon,
            'overview' => $tourType->overview,
            'thumb_alt' => $tourType->thumb_alt,
            'banner_alt' => $tourType->alt,
            'banner' => asset('photos/' . $tourType->_thumb),
            'seo' => [
                'title' => $tourType->seo_title,
                'keywords' => $tourType->seo_keywords,
                'robots' => $tourType->seo_robots,
                'description' => $tourType->seo_description,
                'facebook_title' => $tourType->og_title,
                'schema' => $tourType->seo_schema,
                'facebook_description' => $tourType->facebook_description,
                'twitter_title' => $tourType->twitter_title,
                'twitter_description' => $tourType->twitter_description,
                'twitter_image' => asset('photos/' . $tourType->_twitter_image),
                'facebook_image' => asset('photos/' . $tourType->_facebook_image),
            ],
            'packages' => $packages
        ];

        return response()->json($response);
    }
}
