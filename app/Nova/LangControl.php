<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class LangControl extends Resource
{

    public static $model = \App\LangControl::class;


    public static $title = 'id';

    public static $group = 'Global Settings';
    public static $priority = 2;

    public static function label()
    {
        return 'Language Control';
    }


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
    public function create()
    {
        return false;
    }
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/langcontrols.png" />';
    }


    public static $search = [
        'id',
    ];


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            Boolean::make('English'),
            Boolean::make('Spanish'),
            Boolean::make('Italian'),
            Boolean::make('French'),
            Boolean::make('Deutsch'),
            Boolean::make('Russian'),
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
