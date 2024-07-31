<?php

namespace App\Nova;

use App\Nova\Traits\RedirectAfterAction;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\TabsOnEdit;
use Google\Service\Forms\TextLink;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Monaye\SimpleLinkButton\SimpleLinkButton;
use Waynestate\Nova\CKEditor;

class About extends Resource
{
    use RedirectAfterAction;
    use TabsOnEdit;
    use TranslatableTabToRowTrait;
    public static $model = \App\About::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public static $group = 'Global Settings';
    public static $priority = 1;

    public static function label()
    {
        return 'Home Content';
    }

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/aboutus.png" />';
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public static $search = [
        'id','title'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [

            ID::make(__('ID'), 'id')->hideFromIndex()->sortable(),


            NovaTabTranslatable::make([
                Text::make('First Title'),
                CKEditor::make('First Block')->hideFromIndex(),
                Text::make('Second Title'),
                CKEditor::make('Second Block')->hideFromIndex(),
                Text::make('Third Title','title')
                    ->sortable()
                    ->rules('required_lang:en', 'max:255'),
                CKEditor::make('Third Block','description')->rules('required_lang:en')->hideFromIndex(),

            ]),
            // MediaLibrary::make('Video')->types('video')->hideFromIndex(),
            FilemanagerField::make('Video')->hideFromIndex(),

        ];


    }



    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
