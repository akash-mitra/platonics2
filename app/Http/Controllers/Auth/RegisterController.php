<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Socialite;
use Auth;
use App\LoginProvider;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * list of social drivers enabled for Social Auth
     */
    protected $drivers = [
        'google', 'facebook', 'twitter', 'github'
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => 'Regular',
        ]);
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        if (! in_array($provider, $this->drivers)) {
            return back()->with('status', 'Something went wrong');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from OAuth Provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        if (! in_array($provider, $this->drivers)) {
            return back()->with('status', 'Missing Provider');
        }
        
        // attempt to retrieve the user from the social provider
        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return back()->with('status', 'Missing User');
        }
        
        // now that the user has been authenticated,
        // check if we already have this user available
        $loginUser = LoginProvider::where('provider_user_id', $providerUser->getId())
                        ->where('provider', $provider)
                        ->first();
        
        // if this login provider for the user already exists
        if ($loginUser) {
            // get the user
            $user = $loginUser->user;
            // update the avatar in user and login provider
            $user->avatar = $providerUser->getAvatar();
            $loginUser->avatar = $providerUser->getAvatar();
            // persist the change
            $user->save();
            $loginUser->save();
        }
        else {
            // if the login provider for the user does not exist
            // even though there is no login provider for this
            // user, but the user might still exist in user
            // table. If the user email exists we should
            // retrieve the user and update the user
            // record with the newy received data
            $user = User::firstOrNew([
                'email' => $providerUser->getEmail()
            ]);
            // do not update the name and type if already there
            $user->name   = empty($user->name)? $providerUser->getName(): $user->name;
            $user->type   = empty($user->type)? 'Regular': $user->type;
            // refresh the avatar everytime
            $user->avatar = $providerUser->getAvatar();
            $user->slug   = uniqid(mt_rand(), true);
            
            // persist the record to database
            $user->save();
            // create the login provider
            $user->providers()->create([
                'provider_user_id' => $providerUser->getId(),
                'provider'         => $provider,
                'avatar'           => $providerUser->getAvatar(),
            ]);
        }

        // finally log the user in
        Auth::login($user, true);
        return redirect('/');
    }
}
