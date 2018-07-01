<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'slug',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'id', 'email',
    ];

    /**
     * returns all available social login
     * providers for the user
     */
    public function providers($provider = null)
    {
        if (empty($provider)) {
            return $this->hasMany('App\LoginProvider', 'user_id', 'id');
        } else {
            return $this->hasMany('App\LoginProvider', 'user_id', 'id')->where('provider', $provider);
        }
    }

    /**
     * Current logic simply returns any of the available providers
     */
    public function defaultProvider()
    {
        return $this->providers[0];
    }

    public function pages()
    {
        return $this->hasMany('App\Page');
    }
}
