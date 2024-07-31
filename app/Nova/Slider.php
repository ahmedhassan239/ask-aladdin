<?php

namespace App\Nova;


use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use MrMonat\Translatable\Translatable;
use Whitecube\NovaFlexibleContent\Flexible;

class Slider extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Slider::class;


    public static $title = 'title';
    public static $group = 'Global Settings';
    public static $priority = 5;

    public static function label()
    {
        return 'Home Sliders';
    }
    public function authorizedToView(Request $request)
    {
        return false;
    }
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Sliders.png" />';
    }



    public static $search = [
        'id'
    ];


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),

            Flexible::make('Slider Data')
                ->addLayout('Slider', 'wysiwyg', [
                    Text::make('Title')->translatable([
                        'en' => 'EN',
                        'fr' => 'FR',
                        'es' => 'ES',
                        'de' => 'DE',
                        'it' => 'IT',
                        'ru' => 'RU',
                    ])->rules('required'),
                    Textarea::make('Small Text')->hideFromIndex()->translatable([
                        'en' => 'EN',
                        'fr' => 'FR',
                        'es' => 'ES',
                        'de' => 'DE',
                        'it' => 'IT',
                        'ru' => 'RU',
                    ])->rules('required'),
                    Text::make('Call To Action Title','call_to_action_title')->hideFromIndex()->translatable([
                        'en' => 'EN',
                        'fr' => 'FR',
                        'es' => 'ES',
                        'de' => 'DE',
                        'it' => 'IT',
                        'ru' => 'RU',
                    ]),
                    Text::make('Call To Action Link','call_to_action_link')->hideFromIndex()->translatable([
                        'en' => 'EN',
                        'fr' => 'FR',
                        'es' => 'ES',
                        'de' => 'DE',
                        'it' => 'IT',
                        'ru' => 'RU',
                    ]),
                    FilemanagerField::make('Image Over Video','image_over_video'),
                ])->confirmRemove()->button('Add Slider Data'),
            Text::make('Alt')->hideFromIndex()->translatable([
                'en' => 'EN',
                'fr' => 'FR',
                'es' => 'ES',
                'de' => 'DE',
                'it' => 'IT',
                'ru' => 'RU',
            ]),
            // MediaLibrary::make('Image/Video','image'),
            FilemanagerField::make('Image/Video','video_image'),
            
            Boolean::make('Active','status'),
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
