<?php

namespace App\Nova;

use App\Nova\Filters\DestinationSort;
use Eminiarts\Tabs\TabsOnEdit;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\MultiselectField\Multiselect;

class Footer extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Footer::class;


    public static $title = 'id';

    public static $group = 'Global Settings';
    public static $priority = 7;

    public static function label()
    {
        return 'Footer';
    }

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/footer.png" />';
    }
    public static $search = [
        'id',
    ];


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            NovaTabTranslatable::make([
                Text::make('Title'),
            ]),
            BelongsTo::make('Destination'),
            Multiselect::make('Categories')->options(
                \App\Category::where('destination_id',$this->destination_id)->where('status',1)->where('showed',0)->pluck('name', 'id')
            )        ->reorderable()->hideFromIndex(),
//            BelongsTo::make('Destination'),
//            AjaxSelect::make('Category','category_id')
//                ->get('/api/category/{destination}')
//                ->parent('destination'),
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
