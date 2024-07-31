<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

class MenuApiController extends Controller
{
    public function getAllMenus(){
        $query = Menu::where('status',1)->get();
    
        $destinations =  $query->map(function ($val){
            return [
                'name' => $val->destination->name ?? null,
                'slug' => $val->destination->slug ?? null
            ];
        })->filter(function ($val) {
            return $val['name'] !== null && $val['slug'] !== null;
        })->values();
    
        $hot_offer_packages =  $query->map(function ($val){
            return $val->packages_list;
        })->filter()->values();
        
        $city_excursions =  $query->map(function ($val){
            return $val->excursions_list;
        })->filter()->values();
    
        $travel_guides =  $query->map(function ($val){
            return $val->travel_guides_list;
        })->filter()->values();
    
        $categories =  $query->map(function ($val){
            return $val->categories_list;
        })->filter()->values();
    
        $tour_type =  $query->map(function ($val){
            return $val->tour_type_list;
        })->filter()->values();
    
        return response()->json([
            'data' => [
                'destinations' => $destinations,
                'hot_offer_packages' => $hot_offer_packages,
                'city_excursions' => $city_excursions,
                'travel_guides' => $travel_guides,
                'categories' => $categories,
                'tour_type' => $tour_type,
            ]
        ]);
    }
    
    
}

