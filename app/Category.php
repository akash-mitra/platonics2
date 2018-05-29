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
<<<<<<< HEAD
        return $this->hasMany('App\Page');
=======
>>>>>>> 329ac9fc86a462719e703604b0632da70880f6d3
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
