<?php

namespace App\Nova;

use App\Nova\Filters\DestinationSort;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class SidePhoto extends Resource
{
    use HasSortableRows;

    public static $model = \App\SidePhoto::class;


    public static $title = 'id';
    public static $group = 'Global Settings';
    public static $priority = 4;

    public static function label()
    {
        return 'Side Banner';
    }

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/sidePhotos.png" />';
    }
    public static $search = [
        'id',
    ];


    public function fields(Request $request)
    { $destination = $this->destination->slug ?? '' ;
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            // MediaLibrary::make('Image')->preview('banner'),
            FilemanagerField::make('Image (Max Width 250 px )','large_img')->folder($destination)->displayAsImage(),
            Text::make('Image Alt tag','image_alt')->hideFromIndex(),
            BelongsTo::make('Destination'),
            Select::make('Choose Module','module')->options([
                'articles'=>'Articles',
                'travel-guides'=>'Travel Guides',
                'packages'=>'Travel Packages',
                'blogs'=>'Travel Blogs',
                'categories'=>'Footer Categories',
                'hotels'=>'Hotels',
                'cruises'=>'Cruises',
                'myths-facts'=>'Myths & Facts',
            ]),
            Text::make('HyperLink','link')->rules('url')->hideFromIndex(),

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
