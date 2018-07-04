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
        'publish', 'draft'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    //protected $appends = ['slug', 'metrics'];

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

    /**
     * Get user
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get category
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the slug value from title.
     *
     * @return bool
     */
    public function getSlugAttribute()
    {
        return str_slug($this->title, '-');
    }

    /**
     * Get Page Analytics metrics associated with the page.
     *
     * @return bool
     */
    public function getMetricsAttribute()
    {
        return GoogleAnalytics::metrics($this->id);
    }
}
