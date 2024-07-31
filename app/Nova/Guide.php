<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use MrMonat\Translatable\Translatable;

class __Guide extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Guide::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';
    public static $group = 'Main-Parts';

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Guides.png" />';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','slug','name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [

            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Slug::make('Slug')
                ->from('Name')
                ->separator('_')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Email')
                ->sortable()
                ->rules('required','email','max:255'),
            Translatable::make('Description')->trix()->hideFromIndex(),
            MediaLibrary::make('Photo')->types('image'),
            Boolean::make('Active','status'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [

            new Active
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
