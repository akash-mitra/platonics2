<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginProvider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_user_id',
        'provider',
        'avatar'
    ];
    
    /**
     * returns the user associated with
     * this login provider
     */
    protected function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}