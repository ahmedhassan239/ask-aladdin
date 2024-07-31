<?php

namespace App\Nova;
use App\Nova\Filters\DestinationSort;
use App\Nova\Filters\OptionType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Epartment\NovaDependencyContainer\ActionHasDependencies;
use Epartment\NovaDependencyContainer\HasDependencies;


class Option extends Resource
{
    use HasDependencies;
    public static $model = \App\Option::class;


    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }

    public static $title = 'type';

    public static $group = 'Destinations';
    public static $priority = 8;

    // public static function label()
    // {
    //     return 'Optional trips';
    // }

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Options.png" />';
    }



    public static $search = [
        'id','content','type'
    ];




    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            BelongsTo::make('Destination','destination'),
            Select::make('Type Of Feature','type')->options([
                'include' =>'Included',
                'exclude' =>'Excluded',
                'highlight' =>'Trip Highlight',
                'amenities'=>'Hotel Amenities',
                'service'=>'Service',
                'activity'=>'Activity',
            ])->displayUsingLabels(),
            NovaDependencyContainer::make([
                Select::make('Hotel Category','category')->options([
                    'general_services' =>'Hotel General Services',
                    'safety_security' =>'Hotel Safety & security',
                    'accessibility' =>'Hotel Accessibility',
                    'activities'=>'Hotel Activities',
                    'food_drink'=>'Hotel Food & Drink',
                    'business_facilities'=>'Hotel Business facilities',
                    'bathroom_amenities'=>'Bathroom Amenities',
                ])
            ])->dependsOn('type', 'amenities'),
            
            Text::make('Content'),
        ];


    }


    public function cards(Request $request)
    {
        return [];
    }


    public function filters(Request $request)
    {
        return [
            new OptionType,
            new DestinationSort,
        ];
    }


    public function lenses(Request $request)
    {
        return [];
    }


    public function actions(Request $request)
    {
        return [

        ];
    }
}
