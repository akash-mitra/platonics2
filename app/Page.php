<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'user_id', 'title', 'summary', 
        'metakey', 'metadesc', 'media_url', 'access_level',
    ];
    
    /**
     * Get content of the page
     */
    public function contents()
    {
        return $this->hasOne('App\Content', 'page_id', 'id');
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
