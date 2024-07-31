<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use OptimistDigital\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Flexible;
use Waynestate\Nova\CKEditor;
use Bessamu\AjaxMultiselectNovaField\AjaxMultiselect;
use Infinety\Filemanager\FilemanagerField;

class Package extends Resource
{

    use TabsOnEdit;
    use TranslatableTabToRowTrait;


    public static $model = \App\Package::class;


    public static $title = 'name';
    public static $group = 'Destinations';
    public static $priority = 4;
   

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Packages.png" />';
    }


    public static $search = [
        'name','slug','id'
    ];



    public function fields(Request $request)
    {

        $destination = $this->destination->slug ?? '' ;
        return [
            ID::make()->sortable()->hideFromIndex(),

            new Tabs('Packages',
                [

                    'Basic Information'=> array(
                        // Number::make('Serial No','serial')->rules('required')->hideFromIndex(),
                        NovaTabTranslatable::make([
                            Text::make('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255'),
                            Slug::make('Slug')
                                ->from('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255')->hideFromIndex(),

                            Textarea::make('Description" Max 300 Characters "','description')->rules('required_lang:en','max:300')->hideFromIndex(),
                            Ckeditor::make('Overview')->hideFromIndex(),

                        ]),
                        // Multiselect::make('Category')->options([
                        //     '1'=>'Land only Tours',
                        //     '2'=>'3-7 Nights Packages',
                        //     '3'=>'8-14 Nights Packages',
                        //     '4'=>'15-20 Nights Packages',
                        //     '5'=>'Egypt & Jordan',
                        //     '6'=>'Package With Flights',
                        //     '7'=>'Nile Cruise Packages',
                        //     '8'=>'Dahabiya Packages',
                        //     '9'=>'Meditation Trips (Groups Only)',
                        // ])->reorderable()->hideFromIndex(),
                        BelongsTo::make('Destination','destination') ->sortable(),
                        Multiselect::make('Tour Type')
                        ->options(\App\Tourtype::where('destination_id',$this->destination_id)
                        ->pluck('name', 'id')->toArray())
                        ->reorderable()
                        ->hideFromIndex(), 
                            Select::make('Rate')->options([
                                '1' => '1 Star',
                                '2' => '2 Stars',
                                '3' => '3 Stars',
                                '4' => '4 Stars',
                                '5' => '5 Stars',
                            ])->hideFromIndex(),
                            Text::make('Location Package Map')->hideFromIndex(),
                           
                            Number::make('Duration in Days','days')->hideFromIndex(),
                            Number::make('From (Starting Price)','start_price')->hideFromIndex(),
                            Number::make('Discount')->hideFromIndex(),
                            Boolean::make('Active','status'),
                            Boolean::make('Featured'),
                            Boolean::make('Hot Offer')->hideFromIndex(),
                            Boolean::make('Top Sale')->hideFromIndex(),
                            Boolean::make('Multi Country Package','multi')->hideFromIndex(),
                    ),
                    'Package Itinerary'=>[
                            Flexible::make('Day Data')
                                ->addLayout('Day', 'wysiwyg', [
                                    Number::make('Day Number')->rules('required')->hideFromIndex(),
                                    Translatable::make('Day Title')->rules('required')->hideFromIndex(),
                                    Translatable::make('Day Summary')->rules('required')->trix()->hideFromIndex(),
                                    Boolean::make('BreakFast')->hideFromIndex(),
                                    Boolean::make('Lunch')->hideFromIndex(),
                                    Boolean::make('Dinner')->hideFromIndex(),
                                ])->confirmRemove()->button('Add Day')

                    ],
                    'Highlights'=>[
                     

                        Multiselect::make('Highlight')->options(
                            \App\Option::where('destination_id',$this->destination_id)->where('type','highlight')->pluck('content','id')
                            )->reorderable()->hideFromIndex(),
                        // Checkboxes::make('Highlight')->options(
                        //    \App\Option::where('destination_id',$this->destination_id)->where('type','highlight')->pluck('content','id')
                        // )->hideFromIndex(),
                    ],
                    'Including'=>[
                        Heading::make('Including'),
                        // Checkboxes::make('Included')->options(
                            Multiselect::make('Included')->options(
                            \App\Option::where('destination_id',$this->destination_id)->where('type','include')->pluck('content','id')
                        )->reorderable()->hideFromIndex(),
                        Heading::make('Excluding'),
                        Multiselect::make('Excluded')->options(
                            \App\Option::where('destination_id',$this->destination_id)->where('type','exclude')->pluck('content','id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Prices'=>[
                        Flexible::make('Prices')
                            ->addLayout('Tour Package Price', 'wysiwyg', [
                                Select::make('Season')->options([
                                    'October To December'=>'October To December',
                                    'May To September'=>'May To September',
                                    'January To April'=>'January To April',
                                ]),
                                // Heading::make('Prices For 1 Person'),

                                // Number::make('Double Room Standard','double_room_standard_1')->hideFromIndex(),
                                // Number::make('Single Room Standard','single_room_standard_1')->hideFromIndex(),
                                // Number::make('Double Room Comfort','double_room_comfort_1')->hideFromIndex(),
                                // Number::make('Single Room Comfort','Single_room_comfort_1')->hideFromIndex(),
                                // Number::make('Double Room Deluxe','double_room_deluxe_1')->hideFromIndex(),
                                // Number::make('Single Room Deluxe','Single_room_deluxe_1')->hideFromIndex(),
                                Heading::make('Prices For 2-3 Person'),
                                Number::make('Double Room Standard','double_room_standard_2')->hideFromIndex(),
                                Number::make('Single Room Standard','single_room_standard_2')->hideFromIndex(),
                                Number::make('Double Room Comfort','double_room_comfort_2')->hideFromIndex(),
                                Number::make('Single Room Comfort','Single_room_comfort_2')->hideFromIndex(),
                                Number::make('Double Room Deluxe','double_room_deluxe_2')->hideFromIndex(),
                                Number::make('Single Room Deluxe','Single_room_deluxe_2')->hideFromIndex(),
                                Heading::make('Prices For 4-7 Person'),
                                Number::make('Double Room Standard','double_room_standard_3')->hideFromIndex(),
                                Number::make('Single Room Standard','single_room_standard_3')->hideFromIndex(),
                                Number::make('Double Room Comfort','double_room_comfort_3')->hideFromIndex(),
                                Number::make('Single Room Comfort','Single_room_comfort_3')->hideFromIndex(),
                                Number::make('Double Room Deluxe','double_room_deluxe_3')->hideFromIndex(),
                                Number::make('Single Room Deluxe','Single_room_deluxe_3')->hideFromIndex(),
                                Heading::make('Prices For 7-10 Person'),
                                Number::make('Double Room Standard','double_room_standard_4')->hideFromIndex(),
                                Number::make('Single Room Standard','single_room_standard_4')->hideFromIndex(),
                                Number::make('Double Room Comfort','double_room_comfort_4')->hideFromIndex(),
                                Number::make('Single Room Comfort','Single_room_comfort_4')->hideFromIndex(),
                                Number::make('Double Room Deluxe','double_room_deluxe_4')->hideFromIndex(),
                                Number::make('Single Room Deluxe','Single_room_deluxe_4')->hideFromIndex(),
                            ])->confirmRemove()->button('Add Season'),
                    ],

                    'Travel Experiences'=>[
                        Multiselect::make('Related Packages')->options(
                            \App\Package::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                            Flexible::make('Travel Experiences')
                                ->addLayout('Package Travel Experiences', 'wysiwyg', [

                                    Translatable::make('Travel Experiences')->trix()->hideFromIndex(),

                                ])->confirmRemove()->button('Add Travel Experiences'),

                            Flexible::make('Optional Tours')
                                ->addLayout('Optional Trips & Exentions', 'wysiwyg', [
                                    Translatable::make('Title'),
                                    Translatable::make('Overview')->trix(),
                                    Number::make('price'),
                                    FilemanagerField::make('Image')
                                ])->confirmRemove()->button('Optional Tours'),

                    ],
                    'Hotels' => [
                        Heading::make('Choose Hotels'),
                        Multiselect::make('Standard Hotels')->options(
                            \App\Hotel::where('destination_id',$this->destination_id)->where('status',1)->where('hotel_category',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),

                        Multiselect::make('Comfort Hotels')->options(
                            \App\Hotel::where('destination_id',$this->destination_id)->where('status',1)->where('hotel_category',2)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),

                        Multiselect::make('Deluxe Hotels')->options(
                            \App\Hotel::where('destination_id',$this->destination_id)->where('status',1)->where('hotel_category',3)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),

                        Multiselect::make('Cruise Hotels')->options(
                            \App\Hotel::where('destination_id',$this->destination_id)->where('status',1)->where('hotel_category',4)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                    ],

                    'Images' => [
                        new Panel('Images', $this->images()),
                        
                        FilemanagerField::make('New Trip Dossier File','_tripdossier_file')->hideFromIndex(),
                        Flexible::make('Videos')
                            ->addLayout('Youtube Videos', 'wysiwyg', [
                                Text::make('Youtube Videos'),
                            ])->confirmRemove()->button('Add Youtube Video')
                    ],
                    'General Statistics' =>[
                        //                        Number::make('Reviews')->hideFromIndex(),
                        Number::make('Highlights','number_highlights')->hideFromIndex(),
                        Number::make('Itinerary')->hideFromIndex(),
                        Number::make('Meals')->hideFromIndex(),
                        Number::make('Accommodations')->hideFromIndex(),
                        Number::make('Flights')->hideFromIndex(),
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
