<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Get all of the pages that are assigned this tag.
     */
    public function pages()
    {
        return $this->morphedByMany('App\Page', 'taggable');
    }

    /**
     * Get all of the categories that are assigned this tag.
     */
    public function categories()
    {
        return $this->morphedByMany('App\Category', 'taggable');
    }
}
