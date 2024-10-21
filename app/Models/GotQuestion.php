<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

class GotQuestion extends Model
{
    use HasFactory,Searchable;

    protected $fillable = [
        'question',
        'category_id',
        'sub_category_id',
        'answer',
        'status',
    ];
    protected $appends = ['category_name','sub_category_name'];

    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function getCategoryNameAttribute()
    {
        $category = GQCategory::where('id',$this->category_id)->first();
        return $category->name;
    }
    public function getSubCategoryNameAttribute()
    {
        $subcategory = GQSubCategory::where('id',$this->sub_category_id)->first();
        return $subcategory->name;
    }
}
