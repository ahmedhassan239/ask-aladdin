<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Waynestate\Nova\CKEditor;

class Tourtype extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Tourtype::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static function label()
    {
        return 'Tour Types';
    }
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','name','slug'
    ];
    public static $group = 'Destinations';
    public static $priority = 3;
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Travel-Guide.png" />';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

            new Tabs('Tour Types',
                [
                    'Basic Information'=> array(
                        NovaTabTranslatable::make([
                            Text::make('Name','name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255'),
                            Slug::make('Slug')
                                ->from('Name')
                                ->sortable()
                                ->rules('required_lang:en','max:255')->hideFromIndex(),
                            Textarea::make('Description" Max 150 Characters "','description')->rules('max:150')->hideFromIndex(),
                            CKEditor::make('Overview')->hideFromIndex(),
                         
                        ]),
                        BelongsTo::make('Destination','destination')->sortable(),
                        Text::make('Icon')->hideFromIndex(),
//                        BelongsTo::make('Category','categories'),
                        Boolean::make('Active','status'),
                        // Boolean::make('Featured'),
                    ),
         
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
        return [
            new DestinationSort,
            new Active
        ];
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
