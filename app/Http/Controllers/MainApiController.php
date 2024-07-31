<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Category;
use App\Cruise;
use App\Destination;
use App\EmailSubscription;
use App\Excursion;
use App\Faq;
use App\Footer;
use App\Hotel;
use App\LangControl;
use App\Package;
use App\Page;
use App\SidePhoto;
use App\TravelGuide;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MainApiController extends Controller
{
    public function getSearch($name, $lang)
    {
        app()->setLocale($lang);
        $destinations = Destination::where('name', 'like', "%" . $name . "%")->where('status', 1)->where('featured', 1)->get();
        $destination_data = array();
        foreach ($destinations as $destination) {
            $destination_data[] = [
                'id' => $destination->id,
                'name' => $destination->name,
                'slug' => $destination->slug,
                'thumb_alt' => $destination->thumb_alt,
                'thumb' =>asset('photos/' . $destination->_thumb),
            ];
        }
        $blogs = Blog::with('destination:id,slug')->with('destination')->where('name', 'like', "%" . $name . "%")->where('status', 1)->get();
        $blog_data = array();
        foreach ($blogs as $blog) {
            $blog_data[] = [
                'destination' => [
                    'slug' => $blog->destination->slug ?? []
                ],
                'id' => $blog->id,
                'name' => $blog->name,
                'slug' => $blog->slug,
                // 'slug_destination'=> $blog->destination->slug,
                'thumb_alt' => $blog->thumb_alt,
                'thumb' => asset('photos/' . $blog->_thumb),
            ];
        }
        $packages = Package::where('name', 'like', "%" . $name . "%")->with('destination')->where('status', 1)->get();
        $package_data = array();
        foreach ($packages as $package) {
            $package_data[] = [
                'destination' => [
                    'slug' => $package->destination->slug ?? []
                ],
                'id' => $package->id,
                'name' => $package->name,
                'slug' => $package->slug,
                // 'slug_destination'=> $package->destination->slug,
                'thumb_alt' => $package->thumb_alt,
                'thumb' => asset('photos/' . $package->_thumb),
            ];
        }
        $cruises = Cruise::where('name', 'like', "%" . $name . "%")->with('destination')->where('status', 1)->get();
        $cruise_data = array();
        foreach ($cruises as $cruise) {
            $cruise_data[] = [
                'destination' => [
                    'slug' => $cruise->destination->slug ?? []
                ],
                'id' => $cruise->id,
                'name' => $cruise->name,
                'slug' => $cruise->slug,
                // 'slug_destination'=> $cruise->destination->slug,
                'thumb_alt' => $cruise->thumb_alt,
                'thumb' =>asset('photos/' . $cruise->_thumb),
            ];
        }
        $excursions = Excursion::where('name', 'like', "%" . $name . "%")->with('city', 'destination')->where('status', 1)->get();
        $excursion_data = array();
        foreach ($excursions as $excursion) {
            $excursion_data[] = [
                'city' => [
                    'slug' => $excursion->city->slug ?? []
                ],
                'destination' => [
                    'slug' => $excursion->destination->slug ?? []
                ],
                'id' => $excursion->id,
                'name' => $excursion->name,
                'slug' => $excursion->slug,
                // 'slug_destination'=> $excursion->destination->slug,
                'thumb_alt' => $excursion->thumb_alt,
                'thumb' => asset('photos/' . $excursion->_thumb),
            ];
        }
        $categories = Category::where('name', 'like', "%" . $name . "%")->with('destination')->where('showed', 0)->where('status', 1)->get();
        $category_data = array();
        foreach ($categories as $category) {
            $category_data[] = [
                'destination' => [
                    'slug' => $category->destination->slug ?? []
                ],
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                // 'slug_destination'=> $category->destination->slug,
                'thumb_alt' => $category->thumb_alt,
                'thumb' => asset('photos/' . $category->_thumb),
            ];
        }
        $faqs = Faq::where('name', 'like', "%" . $name . "%")->with('destination')->where('status', 1)->get();
        $faq_data = array();
        foreach ($faqs as $faq) {
            $faq_data[] = [
                'destination' => [
                    'slug' => $faq->destination->slug ?? []
                ],
                'id' => $faq->id,
                'name' => $faq->name,
                'slug' => $faq->slug,
                // 'slug_destination'=> $faq->destination->slug,
                'thumb_alt' => $faq->thumb_alt,
                'thumb' => asset('photos/' . $faq->_thumb),
            ];
        }

        $hotels = Hotel::where('name', 'like', "%" . $name . "%")->with('destination')->where('status', 1)->get();
        $hotel_data = array();
        foreach ($hotels as $hotel) {
            $hotel_data[] = [
                'destination' => [
                    'slug' => $hotel->destination->slug ?? []
                ],
                'id' => $hotel->id,
                'name' => $hotel->name,
                'slug' => $hotel->slug,
                // 'slug_destination'=> $hotel->destination->slug,
                'thumb_alt' => $hotel->thumb_alt,
                'thumb' => asset('photos/' . $hotel->_thumb),
            ];
        }
        $pages = Page::where('name', 'like', "%" . $name . "%")->with('category', 'destination')

            ->where('destination_id', '!=', 8)
            ->where('status', 1)
            ->get();
        $page_data = array();
        foreach ($pages as $page) {
            $page_data[] = [
                'destination' => [
                    'slug' => $page->destination->slug ?? []
                ],
                'category' => [
                    'slug' => $page->category->slug ?? []
                ],
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                // 'slug_destination'=> $page->destination->slug,
                'thumb_alt' => $page->thumb_alt,
                'thumb' => asset('photos/' . $page->_thumb),
            ];
        }
        $travel_guides = TravelGuide::where('name', 'like', "%" . $name . "%")
            ->with('destination')
            ->where('status', 1)->get();

        $travel_guide_data = array();
        foreach ($travel_guides as $travel_guide) {
            $travel_guide_data[] = [
                'destination' => [
                    'slug' => $travel_guide->destination->slug ?? []
                ],
                'id' => $travel_guide->id,
                'name' => $travel_guide->name,
                'slug' => $travel_guide->slug,
                // 'slug_destination'=> $travel_guide->destination->slug,
                'thumb_alt' => $travel_guide->thumb_alt,
                'thumb' => asset('photos/' . $travel_guide->_thumb),
            ];
        }

        $data = [
            'destinations' => $destination_data,
            'blogs' => $blog_data,
            'package' => $package_data,
            'cruise' => $cruise_data,
            'excursion' => $excursion_data,
            'category' => $category_data,
            'faq' => $faq_data,
            'hotel' => $hotel_data,
            'page' => $page_data,
            'travel_guide' => $travel_guide_data,
        ];
        return response()->json([
            'data' => $data
        ], '200');
    }

    /*Footer Api*/
    public function getFooters($id, $lang)
    {

        //   $data =  cache()->rememberForever('cache_footer_'.$id,  function () use ($id,$lang){

        $footers = Footer::whereHas('destination', function ($query) use ($id) {
            $query->where('id', $id)->orWhere('slug->en', $id);
        })->get()->map(function ($val) {
            return [
                'id' => $val->id,
                'title' => $val->title,
                'destination_name' => $val->destination->name,
                'destination_slug' => $val->destination->slug,
                'categories' => $val->categories_list,
            ];
        });

        return response()->json([
            'data' => $footers
        ], '200');
    }

    /*Side Photos*/
    // 
    public function getSidePhotos($dest_id, $module)
    {
        $side_photos = SidePhoto::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })->where('module', '=', $module)
            ->orderBy('sort_order', 'desc')
            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'link' => $value->link,
                    'module' => $value->module,
                    'image_alt' => $value->image_alt,
                    'image' =>asset('photos/' . $value->large_img)
                ];
            });
        return response()->json([
            'data' => $side_photos
        ], '200');
    }

    /*Language Control*/
    public function getLangControl()
    {

        $langs = LangControl::get()->map(function ($val) {
            return [
                'english' => $val->english == 1,
                'french' => $val->french == 1,
                'spanish' => $val->spanish == 1,
                'deutsch' => $val->deutsch == 1,
                'russian' => $val->russian == 1,
                'italian' => $val->italian == 1,
            ];
        });

        return response()->json([
            'data' => $langs
        ], '200');
    }
    /* Subscription Email */
    public function insertEmail(Request $request){
        $validator =Validator::make($request->all(), [
             'email' => 'required|email:rfc,dns',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }else{
            $data = EmailSubscription::create([
                'email'=> $request->email,
            ]);
        }

        return response()->json([
            'data'=>$data,
            'success' => 'success'
        ],'200');
    }

    
}
