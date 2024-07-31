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
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Cruise extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Cruise::class;


    public static $title = 'name';


    public static $search = [
        'id','name','slug'
    ];
    public static $group = 'Destinations';
    public static $priority = 7;

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Cruises.png" />';
    }

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }

    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;

        return [
            ID::make()->sortable()->hideFromIndex(),
            new Tabs('Cruises',
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
                        Number::make('Duration in Days','days')->rules('required')->hideFromIndex(),
                        Number::make('Discount')->hideFromIndex(),
                        BelongsTo::make('Destination','destination'),
//                        BelongsTo::make('Category','categories'),
//                        Country::make('Country','country_code')->hideFromIndex(),
                        Select::make('Rate')->options([
                            '1' => 'Sunday',
                            '2' => '2 Stars',
                            '3' => '3 Stars',
                            '4' => '4 Stars',
                            '5' => '5 Stars',
                        ])->hideFromIndex(),
                        Boolean::make('Active','status'),
                        Boolean::make('Hot Offer')->hideFromIndex(),
                        Boolean::make('Top Sale')->hideFromIndex(),

                    ),
                    'Cruise Itinerary'=>[
                        Select::make('Every Day','every_day_3')->options([
                            'Sunday' => 'Sunday',
                            'Monday' => 'Monday',
                            'Tuesday' => 'Tuesday',
                            'Wednesday' => 'Wednesday',
                            'Thursday' => 'Thursday',
                            'Friday' => 'Friday',
                            'Saturday' => 'Saturday',
                        ])->hideFromIndex(),
                        Flexible::make('3 Nights Itinerary','day_data')
                            ->addLayout('Day', 'wysiwyg', [
                                Number::make('Day Number')->rules('required')->hideFromIndex(),
                                Translatable::make('Day Title')->rules('required')->hideFromIndex(),
                                Translatable::make('Day Summary')->rules('required')->trix()->hideFromIndex(),
                                Boolean::make('BreakFast')->hideFromIndex(),
                                Boolean::make('Lunch')->hideFromIndex(),
                                Boolean::make('Dinner')->hideFromIndex(),
                            ])->confirmRemove()->button('3 Nights Itinerary'),

                            Select::make('Every Day','every_day_4')->options([
                                'Sunday' => 'Sunday',
                                'Monday' => 'Monday',
                                'Tuesday' => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday' => 'Thursday',
                                'Friday' => 'Friday',
                                'Saturday' => 'Saturday',
                            ])->hideFromIndex(),
                            Flexible::make('4 Nights Itinerary','four_nights')
                                ->addLayout('Day', 'wysiwyg', [
                                    Number::make('Day Number')->rules('required')->hideFromIndex(),
                                    Translatable::make('Day Title')->rules('required')->hideFromIndex(),
                                    Translatable::make('Day Summary')->rules('required')->trix()->hideFromIndex(),
                                    Boolean::make('BreakFast')->hideFromIndex(),
                                    Boolean::make('Lunch')->hideFromIndex(),
                                    Boolean::make('Dinner')->hideFromIndex(),
                                ])->confirmRemove()->button('4 Nights Itinerary'),
                                Select::make('Every Day','every_day_7')->options([
                                    'Sunday' => 'Sunday',
                                    'Monday' => 'Monday',
                                    'Tuesday' => 'Tuesday',
                                    'Wednesday' => 'Wednesday',
                                    'Thursday' => 'Thursday',
                                    'Friday' => 'Friday',
                                    'Saturday' => 'Saturday',
                                ])->hideFromIndex(),
                                Flexible::make('7 Nights Itinerary','seven_nights')
                                    ->addLayout('Day', 'wysiwyg', [
                                        Number::make('Day Number')->rules('required')->hideFromIndex(),
                                        Translatable::make('Day Title')->rules('required')->hideFromIndex(),
                                        Translatable::make('Day Summary')->rules('required')->trix()->hideFromIndex(),
                                        Boolean::make('BreakFast')->hideFromIndex(),
                                        Boolean::make('Lunch')->hideFromIndex(),
                                        Boolean::make('Dinner')->hideFromIndex(),
                                    ])->confirmRemove()->button('7 Nights Itinerary')

                    ],
                    'Additional Information'=>[
                        Heading::make('Location Map'),
                        Text::make('Location')->rules('url')->hideFromIndex(),
                        Heading::make('Checkin & Checkout'),
                        Text::make('Checkin')->hideFromIndex(),
                        Text::make('Checkout')->hideFromIndex(),
                        Multiselect::make('Services')->options(
                            \App\Option::where('destination_id',$this->destination_id)->where('type','service')->pluck('content','id')
                        )->reorderable()->hideFromIndex(),
                        Heading::make('Activities'),
                        Multiselect::make('Activities')->options(
                             \App\Option::where('destination_id',$this->destination_id)->where('type','activity')->pluck('content','id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Price List'=>[
                        Number::make('Start From','double_room_price')->rules('required')->hideFromIndex(),
                        Flexible::make('Seasonal Pricing','prices')
                            ->addLayout('Prices', 'wysiwyg', [
                                Select::make('Duration')->options([
                                    '3 Nights 4 Days'=>'3 Nights 4 Days',
                                    '4 Nights 5 Days'=>'4 Nights 5 Days',
                                    '7 Nights 8 Days'=>'7 Nights 8 Days',
                                ]),
                                Select::make('Season')->options([
                                    'October To December'=>'October To December',
                                    'May To September'=>'May To September',
                                    'January To April'=>'January To April',
                                ]),
                                Number::make('Start From'),
                                Number::make('Triple Cabin'),
                                Number::make('Double Cabin'),
                                Number::make('Single Cabin'),
                            ])->confirmRemove()->button('Add Price List')
                    ],
                    'Related Cruises'=>[
                        Multiselect::make('Related Cruises')->options(
                            \App\Cruise::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                        Flexible::make('Travel Experiences')
                            ->addLayout('Cruse Travel Experiences', 'wysiwyg', [

                                Translatable::make('Travel Experiences')->rules('required')->trix()->hideFromIndex(),

                            ])->confirmRemove()->button('Add Travel Experiences')

                    ],
                    // 'Hotels' => [

                    //     Heading::make('Choose Hotels'),
                    //     Multiselect::make('Hotels')->options(
                    //         \App\Hotel::where('destination_id',$this->destination_id)->where('status',1)->where('hotel_category',4)->pluck('name', 'id')
                    //     )->reorderable()->hideFromIndex(),
                    // ],
                    'Images' => [
                        new Panel('Images', $this->images()),
                        Flexible::make('Gallery','_gallery')
                        ->addLayout('Gallery', 'wysiwyg', [
                            Text::make('Alt'),
                            FilemanagerField::make('Gallery')->displayAsImage()->folder($destination)->hideFromIndex(),

                        ])->confirmRemove()->button('Add Gallery')
                    ],
                    'General Statistics' =>[
//                        Number::make('Reviews')->hideFromIndex(),
                        Number::make('Itinerary')->hideFromIndex(),
                        Number::make('Meals')->hideFromIndex(),
                        // Number::make('Accommodations')->hideFromIndex(),
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
