<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\VaporFile;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Excursion extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;


    public static $model = \App\Excursion::class;


    public static $title = 'name';

    public static $group = 'Destinations';
    public static $priority = 5;

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px" src="/icons/Excursions.png" />';
    }

    public static $search = [
        'id','slug','name'
    ];


    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;

        return [
            ID::make()->sortable()->hideFromIndex(),
            new Tabs('Excursions',
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

                            Textarea::make('Description" Max 300 Characters "','description')->rules('required_lang:en','max:300')->hideFromIndex(),
                            CKEditor::make('Overview')->hideFromIndex(),

                        ]),
                        Number::make('Duration')->hideFromIndex(),
                        Number::make('Number Of Travellers','travellers')->hideFromIndex(),
                        //                        Country::make('Country', 'country_code')->hideFromIndex(),
                        Number::make('Discount')->hideFromIndex(),
                        //                        BelongsTo::make('Destination','destinations'),
                        //                        BelongsTo::make('Category','categories'),
                        BelongsTo::make('Destination','destination'),
                        AjaxSelect::make('City','city_id')
                            ->get('/api/city/{destination}')
                            ->parent('destination')->rules('required'),
                        Select::make('Rate')->options([
                            '1' => '1 Star',
                            '2' => '2 Stars',
                            '3' => '3 Stars',
                            '4' => '4 Stars',
                            '5' => '5 Stars',
                        ])->hideFromIndex(),
                        Boolean::make('Active','status'),
                        Boolean::make('Hot Offer')->hideFromIndex(),
                        Boolean::make('Top Sale')->hideFromIndex(),
                    ),
                    'Price List'=>[
                        Number::make('Price For One Person','price_1')->hideFromIndex(),
                        Number::make('Price For 2-3 Persons','price_2_3')->hideFromIndex(),
                        Number::make('Price For 4-6 Persons','price_4_6')->hideFromIndex(),
                        Number::make('Price For 7-10 Person','price_7_10')->hideFromIndex(),
                        Number::make('Price For +11 Person (Start From)','price_11')->hideFromIndex(),
                        Code::make('Code'),

                    ],
                    'Excursion Policy'=>[
                        new Panel('Policy', $this->policy()),
                    ],
                    'Including'=>[
                        
                        Multiselect::make('Included')->options(
                            $includes = \App\Option::where('destination_id',$this->destination_id)->where('type','include')->pluck('content','id')
                        )->hideFromIndex(),
                  
                        Multiselect::make('Excluded')->options(
                            $exclude = \App\Option::where('destination_id',$this->destination_id)->where('type','exclude')->pluck('content','id')
                        )->hideFromIndex(),
                    ],
                    'Related Excursions' => [
                        Heading::make('Choose Related Excursions'),
                        Multiselect::make('Related Excursions')->options(
                            \App\Excursion::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Location' => [
                        Text::make('Arrive Location')->hideFromIndex(),
                        Text::make('Departure Location')->hideFromIndex(),
                        Text::make('Location','location_description')->hideFromIndex(),
                        Text::make('Location URL For Google Map','map_url')->hideFromIndex(),
                    ],
                    'Images' => [

                        new Panel('Images', $this->images()),

                    ],
                    'Gallery' => [

                        // MediaLibrary::make('Gallery')->array()->types([ 'Image'])->hideFromIndex(),
                        Flexible::make('New Gallery','_gallery')
                        ->addLayout('Gallery', 'wysiwyg', [
                            Text::make('Alt'),
                            FilemanagerField::make('Gallery')->displayAsImage()->folder($destination)->hideFromIndex(),

                        ])->confirmRemove()->button('Add Gallery')

                    ],
                    'General Statistics' =>[
                          //  Number::make('Reviews')->hideFromIndex(),
                        Number::make('Meals')->hideFromIndex(),
                        Number::make('Number of Services','services_no')->hideFromIndex(),
                        Number::make('Number Of Activities','activities_no')->hideFromIndex(),
                        Number::make('Guide Tour')->hideFromIndex(),
                    ],
                    // 'Testimonials'=>[
                    //     new Panel('Testimonials', $this->testimonials()),
                    // ],
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
