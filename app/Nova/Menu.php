<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use MrMonat\Translatable\Translatable;
use OptimistDigital\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Flexible;

class Menu extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Menu::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];
    public static $group = 'Global Settings';
    public static $priority = 10;

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    public function authorizedToView(Request $request)
    {
        return false;
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('Destination','destination') ->withMeta(['extraAttributes' => [
                'readonly' => true
          ]]),
            // Multiselect::make('Pages','pages')->options(
            //     \App\Page::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
            // )->reorderable()->hideFromIndex(),
            // Multiselect::make('Blogs','blogs')->options(
            //     \App\Blog::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
            // )->reorderable()->hideFromIndex(),
            Multiselect::make('Page Category Destination','categories')->options(
                \App\Category::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
            )->reorderable()->hideFromIndex(),
            Multiselect::make('Tour Type')->options(
                \App\Tourtype::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
            )->reorderable()->hideFromIndex(),
            Multiselect::make('Hot Offer Packages','packages')->options(
                \App\Package::where('destination_id',$this->destination_id)->where('hot_offer',1)->where('status',1)->pluck('name', 'id')
            )->reorderable()->hideFromIndex(),
         
            Multiselect::make('City Excursions','excursions')->options(
                \App\City::where('destination_id',$this->destination_id)->pluck('name', 'id')
            )->reorderable()->hideFromIndex(),
            // Multiselect::make('Cruise','cruises')->options(
            //     \App\Cruise::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
            // )->reorderable()->hideFromIndex(),
           
            Multiselect::make('Travel Guides')->options(
                \App\TravelGuide::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
            )->reorderable()->hideFromIndex(),
            // Multiselect::make('Hotels','hotels')->options(
            //     \App\Hotel::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
            // )->reorderable()->hideFromIndex(),
            
            // Flexible::make('Special Links','links')
            // ->addLayout('Special Links', 'wysiwyg', [
            //     Translatable::make('Title'),
            //     Translatable::make('Link'),
            //     FilemanagerField::make('Thumb')->displayAsImage()->folder($destination)->hideFromIndex(),
            // ])->confirmRemove()->button('Special Links'),
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
        return [];
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
