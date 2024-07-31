<?php

namespace App\Http\Controllers;


use App\Category;
use App\Destination;
use App\Page;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class PageApiController extends Controller
{
    //    use IsTranslatable;


    //================= get category with pages ===================

    public  function getCategoryWithPages($cat_id, $lang)
    {

        $category = Category::with('pages')
            ->where('id', $cat_id)
            ->orWhere('slug->en', $cat_id)
            ->firstOrFail();
        // dd($category);

        return response()->json([

            'category' => [
                'destination' => [
                    'id' => $category->destination->id,
                    'name' => $category->destination->name,
                    'slug' => $category->destination->slug,
                ],
                'name' => $category->name,
                'slug' => $category->slug,
                'banner' => asset('photos/' . $category->_banner),
                'alt' => $category->alt,
                'description' => $category->description,
                'thumb_alt' => $category->thumb_alt,
                'thumb' => asset('photos/' . $category->_thumb),
                'image_over_banner' => asset('photos/' . $category->_image_over_banner),
                'accordion' => $category->accordion_list,
                'form' => $category->form,
                'related_pages_list' => $category->related_pages_list ?? [],

                'seo' => [
                    'title' => $category->seo_title,
                    'keywords' => $category->seo_keywords,
                    'robots' => $category->seo_robots,
                    'description' => $category->seo_description,
                    'facebook_description' => $category->facebook_description,
                    'twitter_title' => $category->twitter_title,
                    'twitter_description' => $category->twitter_description,
                    'facebook_title' => $category->og_title,
                    'twitter_image' => asset('photos/' . $category->_twitter_image),
                    'facebook_image' => asset('photos/' . $category->_facebook_image),
                ],
                'pages' => $category->pages->map(function ($page) {
                    return [
                        'category' => [
                            'id' => $page->category_id,
                            'slug' => $page->category->slug,
                            'name' => $page->category->name,
                        ],
                        'destination' => [
                            'slug' => $page->destination->slug,
                            'name' => $page->destination->name,
                        ],
                        'id' => $page->id,
                        'name' => $page->name,
                        'slug' => $page->slug,
                        'page_title' => $page->page_title,
                        'thumb_alt' => $page->thumb_alt,
                        'thumb' => asset('photos/' . $page->_thumb),
                    ];
                })
            ],
        ]);
    }

    public  function getCategoryWithPagesv($dist_id, $cat_id, $lang)
    {
        $dest = Destination::where('id', $dist_id)
            ->orWhere('slug->en', $dist_id)
            ->firstOrFail();

        // Next, find the Category using the found destination's id and other parameters.
        $category = Category::with('pages')
            ->where('destination_id', $dest->id)
            ->where(function ($query) use ($cat_id) {
                $query->where('id', $cat_id)
                    ->orWhere('slug->en', $cat_id);
            })
            ->firstOrFail();

        return response()->json([

            'category' => [
                'destination' => [
                    'id' => $category->destination->id,
                    'name' => $category->destination->name,
                    'slug' => $category->destination->slug,
                ],
                'name' => $category->name,
                'slug' => $category->slug,
                'banner' => asset('photos/' . $category->_banner),
                'alt' => $category->alt,
                'description' => $category->description,
                'thumb_alt' => $category->thumb_alt,
                'thumb' => asset('photos/' . $category->_thumb),
                'image_over_banner' => asset('photos/' . $category->_image_over_banner),
                'accordion' => $category->accordion_list,
                'form' => $category->form,
                'related_pages_list' => $category->related_pages_list ?? [],

                'seo' => [
                    'title' => $category->seo_title,
                    'keywords' => $category->seo_keywords,
                    'robots' => $category->seo_robots,
                    'description' => $category->seo_description,
                    'facebook_description' => $category->facebook_description,
                    'twitter_title' => $category->twitter_title,
                    'twitter_description' => $category->twitter_description,
                    'facebook_title' => $category->og_title,
                    'twitter_image' => asset('photos/' . $category->_twitter_image),
                    'facebook_image' => asset('photos/' . $category->_facebook_image),
                ],
                'pages' => $category->pages->map(function ($page) {
                    return [
                        'category' => [
                            'id' => $page->category_id,
                            'slug' => $page->category->slug,
                            'name' => $page->category->name,
                        ],
                        'destination' => [
                            'slug' => $page->destination->slug,
                            'name' => $page->destination->name,
                        ],
                        'id' => $page->id,
                        'name' => $page->name,
                        'slug' => $page->slug,
                        'page_title' => $page->page_title,
                        'thumb_alt' => $page->thumb_alt,
                        'thumb' => asset('photos/' . $page->_thumb),
                    ];
                })
            ],
        ]);
    }



    /*Single Page Api*/
    public function getSinglePage($dest_id, $cat_id, $id)
    {
        $page = Page::where('id', $id)
            ->orWhere('slug->en', $id)
            ->firstOrFail();

        // Finding the Category by id or slug
        $cat = Category::where('id', $cat_id)
            ->orWhere('slug->en', $cat_id)
            ->firstOrFail();

        // Finding the Destination by id or slug
        $dest = Destination::where('id', $dest_id)
            ->orWhere('slug->en', $dest_id)
            ->whereHas('pages', function ($q) use ($id) {
                $q->where('id', $id)
                    ->orWhere('slug->en', $id);
            })
            ->with('pages')
            ->firstOrFail();

        $value = $dest->pages->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });

        // Check if $cat_id matches $page->category_id
        if ($page->category_id != $cat->id) {
            abort(404);
        }


        $gallery = array();
        if (is_array($value->_gallery)) {

            foreach ($value->_gallery as $key) {
                if (array_key_exists('gallery', $key['attributes'])) {
                    $image = asset('photos/' . $key['attributes']['gallery']);
                } else {
                    $image = [];
                }
                if (array_key_exists('alt', $key['attributes'])) {
                    $alt = $key['attributes']['alt'];
                } else {
                    $alt = [];
                }
                $gallery[] = [
                    'alt' => $alt,
                    'image' => $image,
                ];
            }
        }
        $data[] = [
            'destination' => [
                'id' => $value['destination']['id'],
                'name' => $value['destination']['name'],
                'slug' => $value['destination']['slug'],
            ],
            'category' => [
                'name' => $page->category->name ?? '',
                'slug' => $page->category->slug ?? '',
                'thumb_alt' => $page->category->thumb_alt ?? '',
                'thumb' => asset('photos/' . $page->category->thumb),
            ],
            'id' => $value->id,
            'name' => $value->name,
            'slug' => $value->slug,
            'page_title' => $value->page_title,
            'description' => $value->description,
            'thumb_alt' => $value->thumb_alt,
            'form' => $value->form,
            'thumb' => asset('photos/' . $value->_thumb),
            'banner' => asset('photos/' . $value->_banner),
            'banner_alt' => $value->alt,
            'gallery' => $gallery, //API::getFiles($value->gallery_list),
            'related_pages_title' => $value->related_pages_title ?? '',
            'related_pages' => $value->related_pages_list ?? [],
            'related_blogs_title' => $value->related_blogs_title ?? '',
            'related_blogs' => $value->related_blogs_list ?? [],
            'related_packages_title' => $value->related_packages_title ?? '',
            'related_packages' => $value->related_packages_list ?? [],
            'related_cruises_title' => $value->related_cruises_title ?? '',
            'related_cruises' => $value->related_cruises_list ?? [],
            'related_excursions_title' => $value->related_excursions_title ?? '',
            'related_excursions' => $value->related_excursions_list ?? [],
            'related_travel_guides_title' => $value->related_travel_guides_title ?? '',
            'related_travel_guides' => $value->related_travel_guide_list ?? [],
            'related_categories_title' => $value->related_categories_title ?? '',
            'related_categories' => $value->related_categories_list ?? [],
            'accordion' => $value->accordion_list ?? [],
            'seo' => [
                'title' => $value->seo_title,
                'keywords' => $value->seo_keywords,
                'robots' => $value->seo_robots,
                'description' => $value->seo_description,
                'facebook_description' => $value->facebook_description,
                'twitter_title' => $value->twitter_title,
                'twitter_description' => $value->twitter_description,
                'facebook_title' => $value->og_title,
                'schema'=>$value->seo_schema,
                'twitter_image' => asset('photos/' . $value->_twitter_image),
                'facebook_image' => asset('photos/' . $value->_facebook_image),
            ],
        ];
        return response()->json([
            'page' => $data,
        ], '200');
    }

    
}
