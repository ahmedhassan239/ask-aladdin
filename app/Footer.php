<?php

namespace App;

use App\Traits\IsTranslatable;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = ['destination_id'];
    use IsTranslatable;
    public $translatable = ['title','destination_name','destination_slug'];

//    protected $appends = ['categories_list'];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');

    }//END OF destination

    public function getCategoriesListAttribute(){

        if ($this->categories != Null) {
            $categories_data =Category::whereIn('id',json_decode($this->categories))->with('destination')->get()
                ->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                ];
            });
            return $categories_data;
        }
    }
}
