<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use Bessamu\AjaxMultiselectNovaField\AjaxMultiselect;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Page extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Page::class;


    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Pages.png" />';
    }

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public static $title = 'name';
    public static $group = 'Content';
    public static $priority = 1;

    public static $search = [
        'id','name','slug'
    ];


    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;
        $relatedCategoriesOptions = ($this->destination_id != 8) 
        ? \App\Category::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\Category::where('status', 1)->pluck('name', 'id');

        $relatedPagesOptions = ($this->destination_id != 8) 
        ? \App\Page::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\Page::where('status', 1)->pluck('name', 'id');

        $relatedBlogsOptions = ($this->destination_id != 8) 
        ? \App\Blog::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\Blog::where('status', 1)->pluck('name', 'id');

        $relatedBlogsOptions = ($this->destination_id != 8) 
        ? \App\Blog::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\Blog::where('status', 1)->pluck('name', 'id');

        $relatedTravelGuidesOptions = ($this->destination_id != 8) 
        ? \App\TravelGuide::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\TravelGuide::where('status', 1)->pluck('name', 'id');

        $relatedPackagesOptions = ($this->destination_id != 8) 
        ? \App\Package::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\Package::where('status', 1)->pluck('name', 'id');

        $relatedCruisesOptions = ($this->destination_id != 8) 
        ? \App\Cruise::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\Cruise::where('status', 1)->pluck('name', 'id');

        $relatedExcursionsOptions = ($this->destination_id != 8) 
        ? \App\Excursion::where('destination_id', $this->destination_id)->where('status', 1)->pluck('name', 'id')
        : \App\Excursion::where('status', 1)->pluck('name', 'id');
        return [
            ID::make()->sortable()->hideFromIndex(),

            new Tabs('Pages',
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
                            Text::make('Page Title')
                                ->rules('required_lang:en', 'max:255')->hideFromIndex(),

                            CKEditor::make('Description')->rules('required_lang:en')->hideFromIndex(),
                        ]),
                        BelongsTo::make('Destination') ->sortable()->rules('required'),
//                        AjaxSelect::make('Category','category_id')
//                            ->get('/api/category/{destination}')
//                            ->parent('destination'),


                        AjaxSelect::make('Category','category_id')
                            ->get('/api/category/{destination}')
                            ->parent('destination')->rules('required'),


                        Boolean::make('Active','status'),
                    ),
                    'Accordion'=>[
                        Flexible::make('Accordion')
                            ->addLayout('Accordion', 'wysiwyg', [
                                Translatable::make('Title')->required(),
                              //  Translatable::make('Description')->trix()->required(),
                                CKEditor::make('Description')->translatable([
                                    'en' =>'EN',
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
                        Heading::make('Choose Related Category'),
                        Translatable::make('Related Categories Title  (if there is no entry, it will be a default value)','related_categories_title')->hideFromIndex(),
                        Multiselect::make('Related Categories')->options($relatedCategoriesOptions)->reorderable()->hideFromIndex(),
                        Heading::make('Choose Related Articles')->hideFromIndex(),
                        Translatable::make('Related Articles Title  (if there is no entry, it will be a default value)','related_pages_title')->hideFromIndex(),
                        Multiselect::make('Related Articles','related_pages')->options($relatedPagesOptions)->reorderable()->hideFromIndex(),
                        Heading::make('Choose Related Blogs'),
                        Translatable::make('Related Blogs Title  (if there is no entry, it will be a default value)','related_blogs_title')->hideFromIndex(),
                        Multiselect::make('Related Blogs')->options($relatedBlogsOptions)->reorderable()->hideFromIndex(),
                        Heading::make('Related Travel Guides'),
                        Translatable::make('Related Travel Guides Title  (if there is no entry, it will be a default value)','related_travel_guides_title')->hideFromIndex(),
                        Multiselect::make('Related Travel Guides')->options($relatedTravelGuidesOptions)->reorderable()->hideFromIndex(),
                    ],
                    'Related Packages' => [
                        Heading::make('Choose Related Packages'),
                        Translatable::make('Related Packages Title  (if there is no entry, it will be a default value)','related_packages_title')->hideFromIndex(),
                        Multiselect::make('Related Packages')->options($relatedPackagesOptions)->reorderable()->hideFromIndex(),
                        Heading::make('Choose Related Cruises'),
                        Translatable::make('Related Cruises Title (if there is no entry, it will be a default value)','related_cruises_title')->hideFromIndex(),
                        Multiselect::make('Related Cruises')->options($relatedCruisesOptions)->reorderable()->hideFromIndex(),
                        Heading::make('Choose Related Excursions'),
                        Translatable::make('Related Excursions Title (if there is no entry, it will be a default value)','related_excursions_title')->hideFromIndex(),
                        Multiselect::make('Related Excursions')->options($relatedExcursionsOptions)->reorderable()->hideFromIndex(),
                    ],

                    'Images' => [
                        new Panel('Images', $this->images()),
                    ],
                    'Gallery' => [
                        Flexible::make('New Gallery','_gallery')
                        ->addLayout('Gallery', 'wysiwyg', [
                            Text::make('Alt'),
                            FilemanagerField::make('Gallery')->displayAsImage()->folder($destination)->hideFromIndex(),
                        ])->confirmRemove()->button('Add Gallery')

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
