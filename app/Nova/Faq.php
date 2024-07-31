<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use NovaAjaxSelect\AjaxSelect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Faq extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Faq::class;


    public static $title = 'name';
    public static $group = 'Content';
    public static $priority = 4;

    public static function label()
    {
        return "Myths & Facts";
    }

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px" src="/icons/FAQs.png" />';
    }


    public static $search = [
        'id','name','slug'
    ];


    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;

        return [
            ID::make()->sortable()->hideFromIndex(),
            NovaTabTranslatable::make([
                    Text::make('Name','name')
                        ->sortable()
                ]),
            BelongsTo::make('Destination'),
//            AjaxSelect::make('Category','category_id')
//                ->get('/api/category/{destination}/faq')
//                ->parent('destination'),
            Flexible::make('Myths & Facts','faq')
                ->addLayout('Myths & Facts', 'wysiwyg', [
                    Translatable::make('Myth'),
                    Translatable::make('Fact'),
                    Translatable::make('Description','overview')->trix(),
                    // MediaLibrary::make('Image'),
                    FilemanagerField::make('Image','new_image')->displayAsImage()->folder($destination)->hideFromIndex(),

                ])->confirmRemove()->button('Add Myths & Facts'),
            Boolean::make('Active','status'),
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
