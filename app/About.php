<?php

namespace App;

use App\Observers\AboutObserver;
use App\Traits\IsTranslatable;
//use BaseObserver;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class About extends Model
{
    public $table = "abouts";
//    use HasTranslations;
    use IsTranslatable;
    public $translatable = ['title','description','second_title','second_block','first_title','first_block'];

    protected static function boot()
    {
        parent::boot();

        static::observe(AboutObserver::class);
    }

}
