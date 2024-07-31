<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;

class TravelGuide extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\TravelGuide::class;
    public static $group = 'Content';
    public static $priority = 2;

    public static $title = 'name';
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px" src="/icons/Travel-Guide.png" />';
    }

    public static $search = [
        'name','slug','id'
    ];

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            new Tabs('Travel Guides',
                [
                    'Basic Information'=> array(
                        NovaTabTranslatable::make([
                            Text::make('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255'),
                            Slug::make('Slug')
                                ->from('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255')->hideFromIndex(),
                            Textarea::make('Description" Max 150 Characters "','description')->rules('required_lang:en','max:150')->hideFromIndex(),
                            CKEditor::make('Overview')->hideFromIndex(),
                        ]),
                        BelongsTo::make('Destination','destination') ->sortable(),
                        Boolean::make('Active','status'),
//                        Boolean::make('Featured'),
                    ),
                    'Related Travel Guides'=>[
                        Multiselect::make('Related Travel Guides')->options(
                            \App\TravelGuide::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),

                        Multiselect::make('Related Pages')->options(
                            \App\Page::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),

                        Multiselect::make('Related Packages')->options(
                            \App\Package::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Images' => [
                        new Panel('Images', $this->images()),
                    ],

                    'Seo' => [
                        new Panel('Seo', $this->seo()),
                    ],
                    'SEO Schema' => [
                        new Panel('SEO Schema', $this->seoSchema()),
                    ],
            ])
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
