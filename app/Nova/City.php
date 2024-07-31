<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use Laravel\Nova\Panel;
use Eminiarts\Tabs\Tabs;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\Textarea;

class City extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\City::class;


    public static $title = 'name';
    public static $group = 'Destinations';
    public static $priority = 2;


    public static $search = [
        'id','name','slug',
    ];
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/cities.png" />';
    }


    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }


    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;
        return [
            ID::make(__('ID'), 'id')->hideFromIndex()->sortable(),
            
            new Tabs('City',
            [
                'Basic Information'=> array(
                    NovaTabTranslatable::make([
                        Text::make('Name','name')
                        ->sortable()
                        ->rules('required_lang:en','max:255'),
                        Slug::make('Slug')
                        ->from('Name')
                        ->sortable()
                        ->rules('required_lang:en', 'max:255'),
                        Trix::make('Description for Excursions','description'),
                        Trix::make('Description for Hotels','hotel_description'),
                    ]),
                    BelongsTo::make('Destination','destination'),
                ),
                'Images' => [
                    NovaTabTranslatable::make([
                        Text::make('Alt')->hideFromIndex(),
                        Text::make('Thumb Alt')->hideFromIndex(),
                    ]),
                    // MediaLibrary::make('Banner')->types(['image'])->hideFromIndex(),
                    FilemanagerField::make('Banner','_banner')->displayAsImage()->folder($destination)->hideFromIndex(),
                    // MediaLibrary::make('Thumb')->types(['image'])->hideFromIndex(),
                    FilemanagerField::make('Thumb','_thumb')->displayAsImage()->folder($destination)->hideFromIndex(),
                ],
                'Seo for Excursions' => [
                    new Panel('Seo for Excursions', $this->seo()),
                ],
                'Seo for Hotels' => [
                    NovaTabTranslatable::make([
                        Text::make('Page Title','hotel_seo_title')->hideFromIndex(),
                        Text::make('Meta Keywords','hotel_seo_keywords')->hideFromIndex(),
                        Text::make('Robots','hotel_seo_robots')->hideFromIndex(),
                        Textarea::make('Meta Description','hotel_seo_description')->hideFromIndex(),
                        Text::make('Facebook Title','hotel_og_title')->hideFromIndex(),
                        Textarea::make('Facebook Description','hotel_facebook_description')->hideFromIndex(),
                        Text::make('Twitter Title','hotel_twitter_title')->hideFromIndex(),
                        Textarea::make('Twitter Description','hotel_twitter_description')->hideFromIndex(),
                    ]),
                    // MediaLibrary::make('Facebook Image','hotel_facebook_image')->hideFromIndex(),
                    FilemanagerField::make('Facebook Image','_hotel_facebook_image')->displayAsImage()->folder($destination)->hideFromIndex(),
                    // MediaLibrary::make('Twitter Image','hotel_twitter_image')->hideFromIndex(),
                    FilemanagerField::make('Twitter Image','_hotel_twitter_image')->displayAsImage()->folder($destination)->hideFromIndex(),
                ],
                'SEO Schema' => [
                    new Panel('SEO Schema', $this->seoSchema()),
                ],
            ]),

        ];
    }


    public function cards(Request $request)
    {
        return [];
    }


    public function filters(Request $request)
    {
        return [
            
            new DestinationSort,
            new Active
        ];
    }


    public function lenses(Request $request)
    {
        return [];
    }


    public function actions(Request $request)
    {
        return [];
    }
}
