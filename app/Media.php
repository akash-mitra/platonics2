<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'base_path', 'filename', 'name',
        'type', 'size', 'optimized',
    ];
}
