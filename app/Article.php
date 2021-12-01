<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $fillable = [
        'heading', 'user_id','category_id', 'tag_id','description', 'photo',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function path(){
        return route('articles.show', $this);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
}
