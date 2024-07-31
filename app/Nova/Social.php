<?php

namespace App\Nova;

use Eminiarts\Tabs\TabsOnEdit;
use Faker\Provider\PhoneNumber;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Social extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Social::class;


    public static $title = 'id';

    public static $group = 'Global Settings';
    public static $priority = 6;

    public static function label()
    {
        return 'Social Links';
    }


    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Socials.png" />';
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public function create()
    {
        return false;
    }


    public static $search = [
        'id',
    ];


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            Text::make('Facebook')->rules('url'),
            Text::make('Twitter')->rules('url')->hideFromIndex(),
            Text::make('Instagram')->rules('url')->hideFromIndex(),
            Text::make('Youtube')->rules('url')->hideFromIndex(),
            Text::make('LinkedIn')->rules('url')->hideFromIndex(),
            Text::make('Pinterest')->rules('url')->hideFromIndex(),
            Text::make('Flickr')->rules('url')->hideFromIndex(),
            Text::make('Egypt Phone','phone1'),
            Text::make('UAE Phone','phone2')->hideFromIndex(),
            Text::make('Mail')->hideFromIndex(),
            NovaTabTranslatable::make([
            Text::make('Egypt Office Address','address1')->hideFromIndex(),
            Text::make('UAE Office Address','address2')->hideFromIndex(),
            ])

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
