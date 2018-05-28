<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'body', 
    ];

    /**
     * Get page the content belongs to
     */
    public function pages()
    {
        return $this->belongsTo('App\Page', 'page_id', 'id');
    }
}
