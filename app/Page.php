<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * Get content of the page
     */
    public function contents()
    {
        return $this->hasOne(App\Content::class, 'id', 'page_id');
    }

    /**
     * Get all of the tags for the page
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * Get all of the page's comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
