<?php

namespace App\Nova;
// use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use App\Nova\Traits\RedirectAfterAction;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use SaintSystems\Nova\ResourceGroupMenu\DisplaysInResourceGroupMenu;
use Laravel\Nova\Resource as NovaResource;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;
use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Code;

abstract class Resource extends NovaResource
{
    use Breadcrumbs;
    use RedirectAfterAction;
    use DisplaysInResourceGroupMenu;
    


    public function seo()
    {
        $destination = $this->destination->slug ?? '' ;
        return[
            NovaTabTranslatable::make([
                Text::make('Page Title','seo_title')->hideFromIndex(),
                Text::make('Meta Keywords','seo_keywords')->hideFromIndex(),
                // Text::make('Robots','seo_robots')->hideFromIndex(),
                Textarea::make('Meta Description','seo_description')->hideFromIndex(),
                Text::make('Facebook Title','og_title')->hideFromIndex(),
                Textarea::make('Facebook Description')->hideFromIndex(),
                Text::make('Twitter Title')->hideFromIndex(),
                Textarea::make('Twitter Description')->hideFromIndex(),
            ]),
            // MediaLibrary::make('Facebook Image')->hideFromIndex(),
            FilemanagerField::make('Facebook Image','_facebook_image')->displayAsImage()->folder($destination)->hideFromIndex(),
            // MediaLibrary::make('Twitter Image')->hideFromIndex(),
            FilemanagerField::make('Twitter Image','_twitter_image')->displayAsImage()->folder($destination)->hideFromIndex(),
        ];
    }

    public function seoSchema(){
        return[
            Code::make('SEO Schema','seo_schema')->hideFromIndex(),
        ];
    }

    public function images(){
        $destination = $this->destination->slug ?? '' ;
        return [
            NovaTabTranslatable::make([
                Text::make('Banner Alt','alt')->hideFromIndex(),
                Text::make('Thumb Alt')->hideFromIndex(),
            ]),
            // MediaLibrary::make('Banner')->types(['image'])->preview('banner')->hideFromIndex(),
            FilemanagerField::make('Banner','_banner')->displayAsImage()->folder($destination)->hideFromIndex(),
            // MediaLibrary::make('Thumb')->types(['image'])->preview('thumb')->hideFromIndex(),
            FilemanagerField::make('Thumb','_thumb')->displayAsImage()->folder($destination)->hideFromIndex(),
            // MediaLibrary::make('Image Over Banner')->types(['image'])->hideFromIndex(),
            FilemanagerField::make('Image Over Banner','_image_over_panner')->displayAsImage()->folder($destination)->hideFromIndex(),
        ];
    }

    public function policy(){
        return [
            NovaTabTranslatable::make([
                CKEditor::make('Price Policy','price_policy')->hideFromIndex(),
                CKEditor::make('Payment Policy','payment_policy')->hideFromIndex(),
                CKEditor::make('Repeated Travellers','repeated_travellers')->hideFromIndex(),
                CKEditor::make('Travel on your own Schedule','travel_schedule')->hideFromIndex(),
            ]),
        ];
    }


    public function testimonials(){
        return[
            Flexible::make('Reviews')
                ->addLayout('Review', 'wysiwyg', [
                    Text::make('Name')->hideFromIndex(),
                    Text::make('Date')->withMeta([
                        'type' => 'date',
                    ])->hideFromIndex(),
                    MediaLibrary::make('Image')->hideFromIndex(),
//                    Country::make('Country')->hideFromIndex(),
                    Select::make('Rate')->options([
                        '1' => '1 Star',
                        '2' => '2 Stars',
                        '3' => '3 Stars',
                        '4' => '4 Stars',
                        '5' => '5 Stars',
                    ])->hideFromIndex(),
                    Trix::make('Review'),
                ])->confirmRemove()->button('Add Review')
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }


    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }


    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }


    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }
}
