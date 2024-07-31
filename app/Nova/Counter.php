<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Counter extends Resource
{

    public static $model = \App\Counter::class;


    public static $title = 'id';

    public static $group = 'Global Settings';
    public static $priority =9;

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
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/counters.png" />';
    }


    public static $search = [
        'id',
    ];


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            Number::make('Tour Listed'),
            Number::make('Verified Agent'),
            Number::make('Satisfied Customer'),
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
