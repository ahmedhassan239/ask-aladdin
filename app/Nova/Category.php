<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Expandable;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;


class Category extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;
    use Expandable;


    public static $model = \App\Category::class;


    public static $title = 'name';

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public static $group = 'Destinations';
    public static $priority = 3;

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Categories.png" />';
    }


    public static $search = [
        'id','name','slug'
    ];




    public function fields(Request $request)
    {
        return[
            new Tabs('Categories',
                [
                    'Basic Information'=> array(
                        ID::make()->sortable()->hideFromIndex(),
                        NovaTabTranslatable::make([
                            Text::make('Name','name')
                                ->sortable()
                                ->rules('required_lang:en','max:255'),
                            Slug::make('Slug')
                                ->from('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255')->hideFromIndex(),
//                            Text::make('Title','title')
//                                ->rules('max:255'),
                            CKEditor::make('Description')->hideFromIndex(),
                            // NovaTinyMCE::make('Description')->hideFromIndex(),

    //                        Multiselect::make('Destinations')->belongsTo(Destination::class),
                        ]),

                        BelongsTo::make('Destination')->sortable(),
                        Boolean::make('Active','status')->rules('required'),
                        Boolean::make('Showed On Destination Landing Page ','showed'),
                        Boolean::make('Faq Category','faq'),
                    ),
                    'Accordion'=>[
                        Flexible::make('Accordion')
                            ->addLayout('Accordion', 'wysiwyg', [
                                Translatable::make('Title')->required(),
                                CKEditor::make('Description')->translatable([
                                    'en' => 'EN',
                                    'fr'=>'FR',
                                    'es'=>'ES',
                                    'de'=>'DE',
                                    'it'=>'IT',
                                    'ru'=>'RU',
                                ])->required(),
                            ])->confirmRemove()->button('Add Accordion'),
                    ],
                    // 'Form' => [

                    //     Code::make('Form')->language('javascript')->hideFromIndex(),

                    // ],
                    'Related Articles' => [
                        Heading::make('Choose Related Articles'),
                        Multiselect::make('Related Articles','related_pages')->options(
                            \App\Page::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Images' => [
//                        NovaTabTranslatable::make([
//
//                            Text::make('Thumb Alt')->hideFromIndex(),
//                        ]),
//
//                        MediaLibrary::make('Thumb')->types(['image'])->hideFromIndex(),
                        new Panel('Images', $this->images()),

                    ],
                    
                    'Seo' => [
                        new Panel('Seo', $this->seo()),
                    ],
                    'SEO Schema' => [
                        new Panel('SEO Schema', $this->seoSchema()),
                    ],
                ]),

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
