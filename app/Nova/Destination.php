<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Panel;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;


class Destination extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;


    public static $model = \App\Destination::class;


    public static $title = 'name';

//    public static $showColumnBorders = true;

//    public static $group = 'Main-Parts';
    public static $group = 'Destinations';
    public static $priority = 1;
    public static $search = [
        'id','slug','name'
    ];

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Destinations.png" />';
    }

    // public static function authorizedToCreate(Request $request)
    // {
    //     return false;
    // }
    // public function authorizedToDelete(Request $request)
    // {
    //     return false;
    // }
    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    // public function create()
    // {
    //     return false;
    // }

    public static function label() {
        return 'Countries';
    }


    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

                new Tabs('Destinations',
                    [
                        'Basic Information'=> array(
                            NovaTabTranslatable::make([
                                Text::make('Name')
                                    ->sortable()
                                    ->rules('required_lang:en', 'max:255'),
                                Slug::make('Slug')
                                    ->from('Name')
                                    ->sortable()
                                    ->rules('required_lang:en', 'max:255'),
                                CKEditor::make('Description')->rules('required_lang:en')->hideFromIndex(),

                            ]),

                            // MediaLibrary::make('Icon')->hideFromIndex(),
                            Boolean::make('Active','status'),
                            Boolean::make('Featured'),
                        ),

                        'Related Pages' => [
                            Heading::make('Choose Related Pages'),
                            Multiselect::make('Related Pages')->options(
                                \App\Page::where('destination_id',$this->id)->where('status',1)->pluck('name', 'id')
                            )->hideFromIndex(),

                        ],

                        // 'Hot Offers' => [
                        //     Heading::make('Packages Hot Offers'),
                        //     Multiselect::make('Packages Hot Offers')->options(
                        //         \App\Package::where('status',1)->where('hot_offer',1)->where('destination_id',$this->id)->orderBy('id', 'DESC')->pluck('name', 'id')
                        //     )->reorderable()->hideFromIndex(),

                        //     Heading::make('Excursions Hot Offers'),
                        //     Multiselect::make('Excursions Hot Offers')->options(
                        //         \App\Excursion::where('status',1)->where('hot_offer',1)->where('destination_id',$this->id)->orderBy('id', 'DESC')->pluck('name', 'id')
                        //     )->reorderable()->hideFromIndex(),

                        //     Heading::make('Cruises Hot Offers'),
                        //     Multiselect::make('Cruises Hot Offers')->options(
                        //         \App\Cruise::where('status',1)->where('hot_offer',1)->where('destination_id',$this->id)->orderBy('id', 'DESC')->pluck('name', 'id')
                        //     )->reorderable()->hideFromIndex(),

                        // ],

                        /*
                        'Myths & Facts Description'=> array(
                            NovaTabTranslatable::make([
                                CKEditor::make('Description','myths_facts_description')->hideFromIndex(),

                            ]),
                        ),
                         */
                        'Images' => [

                            new Panel('Images', $this->images()),

                        ],

                        'Seo' => [
                            new Panel('Seo', $this->seo()),
                         ],
                         'SEO Schema' => [
                            new Panel('SEO Schema', $this->seoSchema()),
                        ],

                        ]),


                    HasMany::make('Category'),
                    HasMany::make('Packages'),
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
