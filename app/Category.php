<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Get all of the pages under the category
     */
    public function pages()
    {
        return $this->hasMany('App\Page');
    }

    /**
     * Get all of the tags for the category
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * Get all of the category's comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
