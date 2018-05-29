<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Category for the articles
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'description', 'access_level',
    ];

    /**
     * Get all of the pages under the category
     */
    public function pages()
    {
        return $this->hasMany('App\Page', 'page_id', 'id');
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
