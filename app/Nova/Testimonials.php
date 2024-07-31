<?php

namespace App\Nova;

use ClassicO\NovaMediaLibrary\MediaLibrary;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Testimonials extends Resource
{

    public static $model = \App\Testimonials::class;

    public function authorizedToView(Request $request)
    {
        return false;
    }
    public static $title = 'id';

    public static $group = 'Global Settings';
    public static $priority =8;

    public static function label()
    {
        return 'Partners';
    }


    public static $search = [
        'id',
    ];
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/testimonials.png" />';
    }


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            Text::make('Link')->rules('url'),
            Text::make('Small Image Alt','small_img_alt')->hideFromIndex(),
            FilemanagerField::make('Small Image','small_img')->displayAsImage(),
            Text::make('Large Image Alt','large_img_alt')->hideFromIndex(),
            FilemanagerField::make('Large Image','large_img')->displayAsImage(),
            Boolean::make('Showed on Large Slider','showed_on_large_slider')->hideFromIndex(),

        ];
    }


    public function cards(Request $request)
    {
        return [];
    }


    public function filters(Request $request)
    {
        return [];
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
