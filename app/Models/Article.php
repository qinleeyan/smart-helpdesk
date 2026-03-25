<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'keywords', 'category_id', 'views'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
