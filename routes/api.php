<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Fields\Image;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*Single Package*/

Route::get('package/{dest_id}/{id}/{lang}', 'PackageApiController@gitSinglePackage');


/*Packages*/
Route::get('packages/{lang}', 'PackageApiController@getAllPackages');
Route::get('multi-country-packages/{lang}', 'PackageApiController@getMultiCountryPackages');

/*sliders*/

Route::get('sliders/{lang}', 'SliderApiController@getAllSliders');

Route::get('sunmarine', 'SliderApiController@sunmarine');


/*destinations*/

Route::get('destinations/{lang}', 'DestinationApiController@getDestinations');

/*Single destination*/
Route::get('destination/{id}/{lang}', 'DestinationApiController@getSingleDestinations');
/*Single destination packages */
Route::get('destination/packages/{id}/{lang}', 'DestinationApiController@getSingleDestinationPackages');

/*Single Destination Api  it will contain all pacgetSingleEnDestinationBlogs*/

Route::get('destination/packages/{id}/{cat_id}/{lang}', 'DestinationApiController@getSingleDestinationPackagesWhereCategory');

/*Single destination Excursions */
Route::get('destination/excursions/{id}/{lang}', 'DestinationApiController@getSingleDestinationExcursions');

/*Get Cities Depending On Destination */
Route::get('destination/{dest_id}/cities/{lang}', 'CityController@destinationCities');

/*Get Excursion Depending On City */
Route::get('city/excursion/{city_id}/{lang}', 'CityExcursionController@excursionCities');

/*Single destination Cruises */
Route::get('destination/cruises/{dest_id}/{lang}', 'DestinationApiController@getSingleDestinationCruises');

/*Single destination blogs*/
Route::get('destination/blogs/{id}/{lang}', 'DestinationApiController@getSingleDestinationBlogs');

/*Single Blog*/
Route::get('blog/{dest_id}/{id}/{lang}', 'BlogApiController@getSingleBlog');

/*Home Page Blogs*/
Route::get('home/blog/{lang}', 'BlogApiController@getFeaturedBlogs');

/*Single destination Faqs*/
Route::get('destination/faqs/{id}/{lang}', 'DestinationApiController@getSingleDestinationFaqs');

/*Single Faq*/
Route::get('faq/{id}/{lang}', 'FaqApiController@getSingleFaq');

/*abouts*/
Route::get('abouts/{lang}', 'AboutApiController@getAbouts');

/*Social*/
Route::get('socials/{lang}', 'SocialApiController@getSocials');

/*Single Excursion*/
Route::get('excursion/{dest_id}/{id}/{lang}', 'ExcursionApiController@gitSingleExcursion');
/*All Cruises*/
Route::get('cruises/{lang}', 'CruiseApiController@getAllCruises');

/* Single Cruise */
Route::get('cruise/{dest_id}/{id}/{lang}', 'CruiseApiController@getSingleCruise');

/* All Guides */
Route::get('guides/{lang}', 'GuideApiController@getAllGuides');

/*Single Guide Api*/
Route::get('guide/{id}/{lang}', 'GuideApiController@getSingleGuides');

/* Create All Pages Api where Category Id  */

//Route::get('{cat_id}/pages/{lang}','PageApiController@getAllPages');
Route::get('{cat_id}/pages/{lang}', 'PageApiController@getCategoryWithPages');

Route::get('{dist_id}/{cat_id}/pages/{lang}', 'PageApiController@getCategoryWithPagesv');


/*Single Page  */
Route::get('/page/{dist_id}/{cat_id}/{id}/{lang}', 'PageApiController@getSinglePage');

/*Single Hotel  */
Route::get('hotel/{dest_id}/{id}/{lang}', 'HotelApiController@getSingleHotel');
Route::get('hotels/{dest_id}/{lang}', 'HotelApiController@getDestinationHotels');
Route::get('hotels-cities/{country_id}/{lang}', 'HotelApiController@getHotelsCities');
Route::get('hotels-list/{dest_id}/{city_id}/{lang}', 'HotelApiController@getHotelList');
// routes/api.php

Route::get('hotels/filter/{lang}', 'HotelController@filter');


/*This Rout to Get Categories for specific destination */
Route::get('/categories/{dest_id}/{lang}', 'CategoryApiController@getSingleDestinationCategories');

Route::get('/hot-offer/{dest_id}/{lang}', 'CategoryApiController@destinationHotOffer');



Route::get('/{dist_id}/single_category/{id}/{lang}', 'CategoryApiController@singleDestinatopnCategory');

/*Global Seo Settings Seo*/
Route::get('global-seo/{lang}', 'GlobalSeoApiController@getGlobaleSeo');



/*Single destination Travel Guide */
Route::get('destination/travel-guides/{dest_id}/{lang}', 'DestinationApiController@getSingleDestinationTravelGuides');

/*Single Travel Guide */
Route::get('travel-guide/{dest_id}/{id}/{lang}', 'TravelGuideApiController@getSingleTravelGuide');

/*Counter Api*/
Route::get('/counter', 'CounterApiController@getCounter');

/*Testimonials*/
Route::get('/testimonials', 'TestimonialApiController@getTestimonials');

/*Search Api*/
Route::get('/search/{name}/{lang}', 'MainApiController@getSearch');
/*Footer Api*/
Route::get('/destination/footer/{id}/{lang}', 'MainApiController@getFooters');

/*Side Photos Api*/
// /{dest_id}/{module}
Route::get('/side-photos/{dest_id}/{module}/{lang}', 'MainApiController@getSidePhotos');
/*Language Control Api*/
Route::get('/lang-control', 'MainApiController@getLangControl');
Route::get('/menus/{lang}', 'MenuApiController@getAllMenus');

/*Page Ajax Select */
Route::get('/category/{destination_id}', function ($destination_id) {

    $destination = \App\Destination::find($destination_id);
    //    dd($destination->category);

    return $destination->category->map(function ($category) {
        return ['value' => $category->id, 'display' => $category->name];
    });
});

/*Excursion Ajax Select */
Route::get('/city/{destination}', function ($destination_id) {

    $destination = \App\Destination::find($destination_id);
    //    dd($destination->category);

    return $destination->city->map(function ($city) {
        return ['value' => $city->id, 'display' => $city->name];
    });
});


/*Faq Ajax Select */
Route::get('/category/{destination_id}/faq', function ($destination_id) {

    $destination = \App\Destination::find($destination_id);
    return $destination->category_faq->map(function ($category) {
        return ['value' => $category->id, 'display' => $category->name];
    });
});

/*Package Filter Api*/

Route::get('/filter-package/{dest_id}/{min_price}/{max_price}/{min_days}/{max_days}/{min_rate}/{max_rate}/{lang}', 'FilterApiController@packageFilter');


/*Excursion Filter Api*/

Route::get('/filter-excursion/{dest_id}/{min_price}/{max_price}/{min_days}/{max_days}/{min_rate}/{max_rate}/{city_id}/{lang}', 'FilterApiController@excursionFilter');

/*Cruise Filter Api*/

Route::get('/filter-cruise/{dest_id}/{min_price}/{max_price}/{min_days}/{max_days}/{min_rate}/{max_rate}/{lang}', 'FilterApiController@cruiseFilter');
/*Download Trip Dossier Form Api*/
Route::post('/download/package/{id}/tripdossier', 'PackageTripdossierApiController@storePackageTripdossier');
Route::post('/email-subscription', 'MainApiController@insertEmail');
Route::get('/email', 'PackageTripdossierApiController@EmailPackageTripdossier');
Route::get('/tour-types/{dest_id}/{lang}', 'TourtypeApiController@tourTypeslist');
Route::get('/single-tour-type/{dest_id}/{id}/{lang}', 'TourtypeApiController@getSingleTourType');


