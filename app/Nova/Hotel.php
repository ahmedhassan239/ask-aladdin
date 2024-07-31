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
use Laravel\Nova\Fields\Country;

use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

use Laravel\Nova\Panel;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\MultiselectField\Multiselect;
use R64\NovaFields\Select;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Hotel extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Hotel::class;


    public static $title = 'name';

    public static $group = 'Destinations';
    public static $priority = 6;

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Hotels.png" />';
    }


    public static $search = [
        'id','name','slug',
    ];

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }


    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;

        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            new Tabs('Hotels',
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

                            Textarea::make('Description Max 350 Character','description')->rules('required_lang:en', 'max:350')->hideFromIndex(),
                            CKEditor::make('Overview')->hideFromIndex(),

                        ]),
//                        MediaLibrary::make('Logo')->hideFromIndex(),
                        Number::make('Start Price')->hideFromIndex(),
                        Select::make('Rate')->options([
                            '1' => '1 Star',
                            '2' => '2 Stars',
                            '3' => '3 Stars',
                            '4' => '4 Stars',
                            '5' => '5 Stars',
                        ]),

                        Select::make('Hotel Category')->options([
                            '1' => 'Standard Hotel',
                            '2' => 'Comfort Hotel',
                            '3' => 'Deluxe Hotel',
                            '4' => 'Cruise Hotel',
                        ])->hideFromIndex(),
                        BelongsTo::make('Destination','destination'),
                        AjaxSelect::make('City','city_id')
                            ->get('/api/city/{destination}')
                            ->parent('destination')->rules('required'),
                        Text::make('Location Map','location')->hideFromIndex(),//->rules('url')
                        Boolean::make('Active','status'),
                    ),
                    'Additional Information' => [
                        Heading::make('Checkin & Checkout'),
                        Text::make('Checkin')->hideFromIndex(),
                        Text::make('Checkout')->hideFromIndex(),
                        
                        Multiselect::make('Hotel Amenities','amenities')->options(
                             \App\Option::where('destination_id', 8 )->where('type','amenities')->pluck('content','id')
                        )->hideFromIndex(),
                        
                        // Multiselect::make('Services')->options(
                        //     \App\Option::where('destination_id',$this->destination_id)->where('type','service')->pluck('content','id')
                        // )->hideFromIndex(),
                      
                        // Multiselect::make('Activities')->options(
                        //     \App\Option::where('destination_id',$this->destination_id)->where('type','activity')->pluck('content','id')
                        // )->hideFromIndex(),
                    ],
                    'Images' => [

                        new Panel('Images', $this->images()),

                    ],
                    'Gallery' => [

                        // MediaLibrary::make('Gallery')->array()->types(['Image'])->hideFromIndex(),
                       Flexible::make('Gallery','_gallery')
                        ->addLayout('Gallery', 'wysiwyg', [
                            Text::make('Alt'),
                            FilemanagerField::make('Gallery')->displayAsImage()->folder($destination)->hideFromIndex(),

                        ])->confirmRemove()->button('Add Gallery')

                    ],
                    'Most popular facilities' => [
                        Boolean::make('Free Parking')->hideFromIndex(),
                        Boolean::make('Free Wifi')->hideFromIndex(),
                        Boolean::make('Air Condition')->hideFromIndex(),
                        Boolean::make('Pool')->hideFromIndex(),
                        Boolean::make('Gym')->hideFromIndex(),
                        Boolean::make('Bathtub')->hideFromIndex(),
                        Boolean::make('Bar')->hideFromIndex(),
                        Boolean::make('Spa And Wellness Centre','spa_and_wellness_centre')->hideFromIndex(),
                        Boolean::make('Family Rooms')->hideFromIndex(),
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
